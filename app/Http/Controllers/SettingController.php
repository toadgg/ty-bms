<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Project;
use Carbon\Carbon;
use Config;
use DB;
use Storage;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function settingSync() {

        $remoteProjects = $this->getRemoteProjects();
        $remoteContracts = $this->getRemoteContracts();

        $projects = Project::all();
        $contracts = Contract::all();

        $summary = [
            'rpcount' => count($remoteProjects),
            'pcount' => count($projects),
            'psync' => $projects->max('sync_at'),
            'rccount' => count($remoteContracts),
            'ccount' => count($contracts),
            'csync' => $contracts->max('sync_at'),
        ];
        $ncdb = Config::get('database.connections.nc56');
        return view('setting.sync', compact('ncdb', 'summary'));
    }

    public function syncAll() {

        $remoteProjects = $this->getRemoteProjects()->keyBy('sync_pk');
        $projects = Project::all();
        foreach ($remoteProjects as $syncPk => $remoteProject) {
            $project = $projects->where('sync_pk', $syncPk)->first();
            $inputs = collect($remoteProject)->toArray();
            $inputs['sync_at'] = Carbon::now();
            $inputs['sync_from'] = 'ncdb';
            if ($project) {
                $project->update($inputs);
            } else {
                $project = Project::create($inputs);
            }
            $remoteProject->id = $project->id;
        }

        $remoteContracts = $this->getRemoteContracts()->keyBy('sync_pk');
        $contracts = Contract::all();
        foreach ($remoteContracts as $syncPk => $remoteContract) {
            $contract = $contracts->where('sync_pk', $syncPk)->first();
            $inputs = collect($remoteContract)->toArray();
            $inputs['sync_at'] = Carbon::now();
            $inputs['sync_from'] = 'ncdb';

            $content = $inputs['content'] ? $inputs['content'] : "无";
            $payment_summary = $inputs['payment_summary'] ? $inputs['payment_summary'] : "无";
            $deposit_summary = $inputs['deposit_summary'] ? $inputs['deposit_summary'] : "无";
            $settle_summary = $inputs['settle_summary'] ? $inputs['settle_summary'] : "无";
            $breach_liability = $inputs['breach_liability'] ? $inputs['breach_liability'] : "无";
            $negotiate_summary = $inputs['negotiate_summary'] ? $inputs['negotiate_summary'] : "无";

            $inputs['content'] =
"### 合同主要内容
$content
### 付款协议摘要
$payment_summary
### 质保金条款
$deposit_summary
### 结算信息
$settle_summary
### 违约责任
$breach_liability
### 合同谈判摘要
$negotiate_summary";

            $inputs['project_id'] = $remoteProjects[$remoteContract->sync_project_pk]->id;

            if ($contract) {
                $contract->update($inputs);
            } else {
                Contract::create($inputs);
            }
        }
        return redirect()->action('SettingController@settingSync');
    }

    private function getRemoteProjects () {
        $projects = DB::connection('nc56')->table('FDC_BD_PROJECT')
            ->leftJoin('BD_CUMANDOC BD_CUMANDOC_1', 'BD_CUMANDOC_1.PK_CUMANDOC', '=', 'FDC_BD_PROJECT.PK_DEVELOPER')
            ->leftJoin('BD_CUBASDOC DEVELOPER_DOC', 'DEVELOPER_DOC.PK_CUBASDOC', '=', 'BD_CUMANDOC_1.PK_CUBASDOC')
            ->leftJoin('BD_CUMANDOC BD_CUMANDOC_2', 'BD_CUMANDOC_2.PK_CUMANDOC', '=', 'FDC_BD_PROJECT.PK_CONTRACTOR')
            ->leftJoin('BD_CUBASDOC CONTRACTOR_DOC', 'CONTRACTOR_DOC.PK_CUBASDOC', '=', 'BD_CUMANDOC_2.PK_CUBASDOC')
            ->leftJoin('JZPM_BD_PROJSTATUS', 'JZPM_BD_PROJSTATUS.PK_PROJSTATUS', '=', 'FDC_BD_PROJECT.PK_PROJSTATUS')
            ->leftJoin('JZPM_BD_PROJTYPE', 'JZPM_BD_PROJTYPE.PK_PROJECTTYPE', '=', 'FDC_BD_PROJECT.PK_PROJTYPE')
            ->selectRaw('FDC_BD_PROJECT.PK_PROJECT as sync_pk,
                     FDC_BD_PROJECT.VNAME as name,
                     DEVELOPER_DOC.CUSTNAME as developer,
                     CONTRACTOR_DOC.CUSTNAME as contractor, 
                     FDC_BD_PROJECT.NCONTMNY as contract_money,
                     FDC_BD_PROJECT.NBUILDAREA as build_area,
                     FDC_BD_PROJECT.DSTART as "start",
                     FDC_BD_PROJECT.dfinish as finish,
                     FDC_BD_PROJECT.PK_PROJMANAGER as manager,
                     JZPM_BD_PROJSTATUS.VNAME as status,
                     JZPM_BD_PROJTYPE.VNAME as type')
            ->whereRaw("nvl(FDC_BD_PROJECT.DR, 0) = 0 and FDC_BD_PROJECT.PK_CORP!='0001'")
            ->get();
        return $projects;
    }

    private function getRemoteContracts () {
        $contracts = DB::connection('nc56')->table('JZPM_CM_CONTRACT')
            ->leftJoin('FDC_BD_PROJECT', 'FDC_BD_PROJECT.PK_PROJECT', '=', 'JZPM_CM_CONTRACT.PK_PROJECT')
            ->leftJoin('JZPM_BD_CONTTYPE', 'JZPM_BD_CONTTYPE.PK_CONTTYPE', '=', 'JZPM_CM_CONTRACT.PK_CONTTYPE')
            ->selectRaw('JZPM_CM_CONTRACT.vname as name,
                    JZPM_CM_CONTRACT.VBILLNO as code,
                    JZPM_BD_CONTTYPE.VNAME type,
                    JZPM_CM_CONTRACT.NPREPAYORIGINMNY as advance_payment_amount,
                    JZPM_CM_CONTRACT.vdef8 as advance_payment_times,
                    JZPM_CM_CONTRACT.NFINISHSETTLESCALE as progress_payment_pct, 
                    JZPM_CM_CONTRACT.ipaymode as pay_mode,
                    JZPM_CM_CONTRACT.NCONTSIGNORIGINMNY as signed_money, 
                    JZPM_CM_CONTRACT.NCURRCONTBASEMNY as current_money,
                    FDC_BD_PROJECT.PK_PROJECT sync_project_pk, 
                    JZPM_CM_CONTRACT.PK_CONTRACT as sync_pk, 
                    JZPM_CM_CONTRACT.dsigndate as signed_date,
                    JZPM_CM_CONTRACT.VCONTENT as content, 
                    JZPM_CM_CONTRACT.VPAYMENTSUMMARY as payment_summary, 
                    JZPM_CM_CONTRACT.VDEPOSITITEM as deposit_summary, 
                    JZPM_CM_CONTRACT.VSETTLEINFO as settle_summary,
                    JZPM_CM_CONTRACT.VOFFENDRESPONSE as breach_liability, 
                    JZPM_CM_CONTRACT.VNEGOTIATESUMMARY as negotiate_summary')
            ->whereRaw("nvl(JZPM_CM_CONTRACT.DR, 0) = 0 and JZPM_BD_CONTTYPE.VCODE = '01'")
            ->get();
        return $contracts;
    }

    public function syncAttachments() {
        $contracts = Contract::all();
        foreach ($contracts as $contract) {
            $filesPath = "%/$contract->sync_pk/文件管理/%";
            $files = DB::connection('nc56')->table('SM_PUB_FILESYSTEM')
                ->where('path', 'like', $filesPath)->get();

            if (Storage::exists("contracts/$contract->id")) {
                Storage::deleteDirectory("contracts/$contract->id");
            }
            foreach ($files as $file) {
                $explode = explode('/',$file->path);
                $fileName = array_pop($explode);
                Storage::put("contracts/$contract->id/$fileName", $file->contentdata);
            }
        }
        return redirect()->action('SettingController@settingSync');
    }
}
