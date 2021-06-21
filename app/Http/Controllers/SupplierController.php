<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Exception;
use DateTime;
use Yajra\Datatables\Datatables;

class SupplierController extends Controller
{
    /* Supplier Home Page */
    public function HomePage()
    {
        return view('pages/supplier_home');
    }
    public function HomeData(Request $request)
    {
        try{
            $data = new Supplier;
            //main search
            if($request->search != ''){
            $data = $data->where('scode', 'LIKE', "%{$request->search}%")
                        ->orWhere('name','LIKE', "%{$request->search}%")
                        ->orWhere('email','LIKE', "%{$request->search}%")
                        ->orWhere('other_phone','LIKE', "%{$request->search}%");
            }
            $data =$data->where('sid','!=','1')->orderBy('sid','desc')->get();

            return Datatables::of($data)->toJson(); //return data table data

        }catch(Exception $e){
            return response(App::environment('local') ? $e->getMessage().$e->getLine() : '' , 422);
        }
    }

    public function New()
    {
        return view('pages/supplier_new')->with(['name'=>'uditha']);
    }

    //Save (New/Edit)
    public function Save(Request $request){
        try{
            //validation rules
            $rules = [
                'sname' => 'required|unique:supplier,name',
                'email' => 'nullable|email|unique:supplier,email',
            ];
            //custom error msg list
            $customMessages = [
                'sname.required'=> 'Supplier Name is required',
            ];
            $validatedData = Validator::make($request->all(), $rules, $customMessages);

            if($validatedData->fails()){
                //return error list to form page with inputs
                return redirect()->back()->withErrors($validatedData)->withInput();
            }else{
                $supplier = new Supplier();
                $supplier->name = $request->sname;
                $supplier->address = $request->saddr;
                $supplier->other_phone = $request->contacts;
                $supplier->email = $request->email;
                $supplier->remark = $request->remark;
                $supplier->sts = '1';

                /* can use traits */
                $date = new DateTime();
                //$date->setTimezone( $timezone );
                $dtdate = $date->format('Y-m-d');
                $supplier->create_date_time = $dtdate;
                $supplier->create_user_id = '1';

                $supplier->save();
                return redirect('/supplier_home');
            }
        }catch(Exception $e){
            dd($e->getMessage());
            //return redirect()->back()->with(['sts'=>'0', 'data'=>App::environment('local') ? $e->getMessage() : [] ])->withInput();
        }
    }

    public function FunctionName()
    {
        $dtts = DB::table('accleg as e')->select('e.aci', DB::raw('SUM(e.dba) as dba, SUM(e.cra) as cra'))->groupBy('e.aci');
        //main query
        return DB::table('accmas as a')->selectRaw('a.aci, a.ali, a.aco, a.ana, a.sts, b.aln, b.ati, a.glt, c.atn, c.agi, d.agn, IF(a.glt = "1",IFNULL(e.dba-e.cra, 0), IFNULL(e.cra-e.dba,0)) as amt')
                        ->leftJoin('acccls as b', 'a.ali', '=', 'b.ali')
                        ->leftJoin('acctyp as c', 'b.ati', '=', 'c.ati')
                        ->leftJoin('accgrp as d', 'c.agi', '=', 'd.agi')
                        ->leftJoinSub($dtts, 'e', function($join){
                            $join->on('a.aci', '=', 'e.aci');
                        })
                        ->orderBy('aci', 'DESC')->get();

        $tab1 = DB::table('accleg as a')
                ->selectRaw('a.rid, DATE_FORMAT(a.det,"%m/%d") AS ddt, a.det, IF(a.tty = "1", b.rno, IF(a.tty = "2", c.vno, IF(a.tty = "3", d.jno, IF(a.tty = "4", e.tno, "error")))) as rno, a.rri, IF(a.tty = "1", b.dcr, IF(a.tty = "2", c.dcr, IF(a.tty = "3", CONCAT(d.dcr," ", a.rmk), IF(a.tty = "4", CONCAT(e.dcr," ",a.rmk), "error")))) as dcr , a.cra, a.rst')
                ->leftJoin(DB::raw("(SELECT CONCAT(IF(b.hbr !='-', b.hbr, ''),' ', IF(b.pyt = '1', f.cna, IF(b.pyt = '2', g.sna, h.ene))) as dcr, b.rno, b.rci FROM `receipt` as b
                LEFT JOIN `cusmas` as f ON f.cid = b.pri
                LEFT JOIN `supmas` as g ON g.sid = b.pri
                LEFT JOIN `empmas` as h ON h.eid = b.pri
                GROUP BY b.rci) as b"), function($join){
                $join->on('b.rci', '=', 'a.tid');
                })
                ->leftJoin(DB::raw("(SELECT CONCAT(IF(c.hbr != '-', c.hbr, '') ,' ', IF(c.pyt = '1', i.cna, IF(c.pyt = '2', j.sna, k.ene))) as dcr, c.vno, c.vid FROM `voucher` as c
                LEFT JOIN `cusmas` as i ON i.cid = c.pei
                LEFT JOIN `supmas` as j ON j.sid = c.pei
                LEFT JOIN `empmas` as k ON k.eid = c.pei
                GROUP BY c.vid) as c"), function($join){
                $join->on('c.vid', '=', 'a.tid');
                })
                ->leftJoin(DB::raw("(SELECT d.sna as dcr, d.jid , d.jno FROM `journal` as d GROUP BY d.jid) as d"), function($join){
                $join->on('d.jid', '=', 'a.tid');
                })
                ->leftJoin(DB::raw("(SELECT CONCAT(e.tno) as dcr, e.tno, e.tid FROM `transactn` as e GROUP BY e.tid) as e"), function($join){
                $join->on('e.tid', '=', 'a.tid');
                })
                ->whereRaw('a.aci = "'.$rqst->acid.'" AND a.cra != "0" AND a.tty != "13" AND a.bri = "'.$rqst->brid.'"')
                ->whereIn('a.rst', array('1', '0'));

        return DB::select('SELECT a.cni, a.cnn, a.stm, a.etm, a.cps, b.cnt, (a.cps-IFNULL(b.cnt, 0)) as cpc FROM `srvjobbkgchnl` as a
            LEFT JOIN (SELECT COUNT(b.cni) as cnt, b.cni FROM `srvjobbokg` as b WHERE b.sts = "1" GROUP BY b.cni) as b ON b.cni = a.cni
            LEFT JOIN (SELECT c.hid, c.det FROM `srvjobhlydy` as c WHERE c.sts = "1") as c ON c.det = "'.$bkdt.'"
            WHERE a.sts = "1" AND c.hid IS NULL AND FIND_IN_SET("'.$bkdi.'", a.ada)  HAVING ((a.cps-IFNULL(b.cnt, 0)) > 0) ORDER BY a.cni ASC');
    }
}
