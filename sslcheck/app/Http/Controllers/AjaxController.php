<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ssl;
use App\Domain;
use Illuminate\Support\Facades\Auth;
class AjaxController extends Controller
{
    public function disableNoti(Request $request){
		foreach ($request->data as $data) {
			if($data !== 'on'){
				$ssl = Ssl::find($data);

				if($ssl->users->id == Auth::user()->id){
					if($ssl->notification == 1){
						$ssl->notification = 0;

						$ssl->save();
					}
				}
			}
		}

		echo "refresh";
    }

    public function enableNoti(Request $request){
		foreach ($request->data as $data) {
			if($data !== 'on'){
				$ssl = Ssl::find($data);
				if($ssl->users->id == Auth::user()->id){
					if($ssl->notification == 0){
						$ssl->notification = 1;

						$ssl->save();
					}
				}
			}
		}

		echo "refresh";
    }

    public function deleteSsl(Request $request){
    	foreach ($request->data as $data) {
			if($data !== 'on'){
				$ssl = Ssl::find($data);
				if($ssl->users->id == Auth::user()->id){
					$ssl->delete();
				}
			}
		}

		echo "refresh";
    }

    public function deleteDomain(Request $request){
    	foreach ($request->data as $data) {
			if($data !== 'on'){
				$domain = Domain::find($data);
				if($domain->users->id == Auth::user()->id){
					$domain->delete();
				}
			}
		}

		echo "refresh";
    }

    public function disableNotiDomain(Request $request){
		foreach ($request->data as $data) {
			if($data !== 'on'){
				$domain = Domain::find($data);

				if($domain->users->id == Auth::user()->id){
					if($domain->notification == 1){
						$domain->notification = 0;

						$domain->save();
					}
				}
			}
		}

		echo "refresh";
    }

    public function enableNotiDomain(Request $request){
		foreach ($request->data as $data) {
			if($data !== 'on'){
				$domain = Domain::find($data);
				if($domain->users->id == Auth::user()->id){
					if($domain->notification == 0){
						$domain->notification = 1;

						$domain->save();
					}
				}
			}
		}

		echo "refresh";
    }
}
