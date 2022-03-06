<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getInfo(Request $request)
    {
        $req = $request->only('fromid', 'toid');

        $frominfo = DB::table('user')->select(['nickname', 'headimgurl'])->where('id', $req['fromid'])->first();
        $toinfo = DB::table('user')->select(['nickname', 'headimgurl'])->where('id', $req['toid'])->first();

        $datas = array();
        $datas['from_info'] = $frominfo;
        $datas['to_info'] = $toinfo;

        return $datas;
    }

    public function loadMessage(Request $request)
    {
        $req = $request->only('fromid', 'toid');

        $datas = DB::table('communication')->orWhere(function($query) use($req){
            $query->where('fromid', $req['fromid']);
            $query->where('toid', $req['toid']);
        })->orWhere(function($query) use($req){
            $query->where('fromid', $req['toid']);
            $query->where('toid', $req['fromid']);
        })->orderBy('id', 'ASC')->get();

        return $datas;
    }

    public function changeNoRead(Request $request)
    {
        $req = $request->only('fromid', 'toid');
        DB::table('communication')->where(['fromid'=>$req['fromid'], 'toid'=>$req['toid']])->update(['isread'=>'1']);
    }

    public function saveMessage(Request $request)
    {
        $req = $request->all();

        $datas['fromid'] = $req['fromid'];
        $datas['fromname'] = $this->getName($datas['fromid']);
        $datas['toid'] = $req['toid'];
        $datas['toname'] = $this->getName($datas['toid']);
        $datas['content'] = $req['data'];
        $datas['time'] = $req['time'];
        //$datas['isread'] = $message['isread'];
        $datas['isread'] = 0;
        $datas['type'] = '1';

        DB::table('communication')->insert($datas);
    }

    public function getName($uid)
    {
        $userinfo = DB::table('user')->select(['nickname'])->where('id', $uid)->first();
        return $userinfo->nickname;
    }

    public function upload(Request $request)
    {
        $files = $request->file();
        $file = array_shift($files);

        $datas = array();

        if($file->isValid()){
            $filename = date('YmdHis').mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/'), $filename);
            $url = url('uploads/'.$filename);

            $req = $request->only('fromid', 'toid', 'online');
            $insert = array();
            $insert['content'] = $url;
            $insert['fromid'] = $req['fromid'];
            $insert['toid'] = $req['toid'];
            $insert['fromname'] = $this->getName($req['fromid']);
            $insert['toname'] = $this->getName($req['toid']);
            $insert['time'] = time();
            $insert['isread'] = $req['online'];;
            $insert['type'] = 2;

            DB::table('communication')->insert($insert);

            $datas['state'] = 0;
            $datas['msg'] = 'SUCCESS';
            $datas['url'] = $url;
        } else {
            $datas['state'] = 1;
            $datas['msg'] = 'ERROR';
        }

        return json_encode($datas);
    }

    public function getHeadOne($uid)
    {
        $fromhead = DB::table('user')->select(['headimgurl'])->where('id', $uid)->first();
        return $fromhead->headimgurl;
    }

    public function getCountNotRead($fromid, $toid)
    {
        return DB::table('communication')->where(['fromid'=>$fromid, 'toid'=>$toid, 'isread'=>'0'])->count('id');
    }

    public function getLastMessage($fromid, $toid)
    {
        $info = DB::table('communication')->orWhere(function($query) use($fromid, $toid){
            $query->where('fromid', $fromid);
            $query->where('toid', $toid);
        })->orWhere(function($query) use($fromid, $toid){
            $query->where('fromid', $toid);
            $query->where('toid', $fromid);
        })->orderBy('id', 'DESC')->first();

        return $info;
    }

    public function getList(Request $request)
    {
        $req = $request->only('id');

        $info = DB::table('communication')->select(['fromid', 'toid', 'fromname'])->where('toid', $req['id'])->groupBy('fromid')->get()->toArray();
        $infos = array_map('get_object_vars', $info);

        $row = array_map(function($res){
            return [
                'head_url'=>$this->getHeadOne($res['fromid']),
                'username'=>$res['fromname'],
                'count_not_read'=>$this->getCountNotRead($res['fromid'], $res['toid']),
                'last_message'=>$this->getLastMessage($res['fromid'], $res['toid']),
                'chat_page'=>'http://local.chat.com?fromid='.$res['toid'].'&toid='.$res['fromid'],
            ];
        }, $infos);

        return $row;
    }
}
