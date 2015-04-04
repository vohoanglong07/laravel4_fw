<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/hello', function(){
    return View::make('hellopage');
});

Route::get('/user', 'UserController@index');

Route::get('/user/show/{id}', 'UserController@show');

View::share('demo', 'Content demo');

View::composer(array('user.show', 'user.index'), 'DemoComposer');

Route::get('admin/news', "controllers\admin\NewsController@index");

Route::get('news', 'NewsController@index');

Route::get('news/detail/{id}', 'NewsController@detail')->where(
    array('id' => '[0-9]+')
);

$action = function($page = '', $id = ''){
    return 'Route/vd1 active . Page : ' . $page . ' - Id : '. $id;
};

Route::get('route/vd1/{page}/{id?}',$action)->where(array(
    'page' => '[a-zA-Z\-]+',
    'id' => '[0-9]+'
));

Route::get('route/vd2/{page}', function($page){
    return View::make('hellopage')->with('page', $page);
});

Route::get('qhonline/test', array('as' => 'qhotest', function(){
    return 'Qho test page';
}));

Route::get('route/user/{id}', array(
    'as' => 'route_user',
    'uses' => 'UsersController@index'
));

Route::group(array('before' => 'checklogin'), function(){
    Route::get('qhonline/vd123', function(){
        return 'qhonline testing';
    });
});


Route::group(array('prefix' => 'qhonline'), function(){
    Route::get('user', function(){
        return 'qhonline testing user';
    });

    Route::get('news', function(){
        return 'qhonline testing news';
    });
});

Route::group(array('domain' => 'jackie.todo.vn'), function(){
    Route::get('my/profile', function(){
        return 'my profile jackie';
    });
});

Route::get('filter/test', function(){
    $str = '<form action="'.URL::to('filter/test_submit').'" method="post">';
    $str .= '<input type="text" name="num" size="15" /><br/>';
    $str .= '<input type="submit" name="fsubmit" value="Submit" />';
    $str .= "</form>";

    return $str;
});

################################
// Before -> Router -> After(not return)
################################
// Route::post('filter/test_submit', array('before' => 'number:'.Input::get('num').'|even', function(){
//     return 'It is OK';
// }));

Route::post('filter/test_submit', array('before' => 'number:'.Input::get('num').'|even', function(){
    return 'It is OK';
}));

Route::when('admin/*', 'checkAdmin');

View::share('title', 'Demo Controller- View - With Laravel Framework');

View::composer(array('news.index', 'news.detail'), function($view){
    $menu = array(
        "1" => "Lập trình PHP",
        "2" => "Đồ Họa",
        "3" => "Phân tích thiết kế hệ thống",
        "4" => "Làm chủ CMS",
    );
    $view->with('menu', $menu);
});

###################################################################
Route::get('layout', 'LayoutController@index');

Route::get('url/full', function(){
    return URL::full();
});

Route::get('url/asset', function(){
   return URL::asset('public/style/main.css', true);
});

Route::get('url/to', function(){
    return URL::to('admin/user', array('edit', 'jackie'), true);
});

Route::get('url/route', function(){
    return URL::route('route_user', array('11'));
});

Route::get('url/action', function(){
    return URL::action('LayoutController@index');
});

include('macros.php');

Route::get('form/create', function(){
    return View::make('form_view');
});

Route::get('validate/form', function(){
    return View::make('formvalid');
});

Route::post('validate/process', function(){
    $data = Input::all();

    $rules = array(
        'username' => 'required|min:3|qhonline:8',
        'password' => 'required|confirmed',
        'email' => 'email'
    );

    $messages = array(
        'username.required' => 'Vui long nhap username',
        'password.required' => 'Vui long nhap mat khau',
        'qhonline' => 'Gia tri nhap vao khong dung',
    );

    $valid = Validator::make($data, $rules, $messages);

    if ($valid->passes()) {
        return 'It is OK';
    } else {
        return Redirect::to('validate/form')->withErrors($valid);
    }
});

//Validator::extend('qhonline', function($field, $value, $params){
//    if ($value == 'qhonline') {
//        return true;
//    } else {
//        return false;
//    }
//});

Validator::extend('qhonline', 'QhonlineValidate@qhonline');

Route::get('res/test', function(){
    //return Redirect::to('res/test2');
    return Redirect::route('res.test2', array('999'));
});

Route::get('res/test2/{id}',array('as' => 'res.test2', function($id){
    return 'Hello Laravel Framework . Id : ' . $id;
}));

Route::get('res/test3', function(){
    $array = array(
        'username' => 'jackie',
        'password' => '12345',
        'level' => 2
    );
    return Response::json($array);
});

Route::get('res/test4', function(){
    return Response::download('url');
});

Route::get('res/test5', function(){
    $res = Response::make('<?xml version="1.0"?><root><data>Hello Laravel Framework</data></root>', '200');
    $res->headers->set('content-type', 'text/xml');
    return $res;
});

Route::get('input/form', function(){
    return View::make('form_input');
});

Route::post('input/process', function(){
    $data = Input::except('user', 'pass');

    echo '<pre>';
    print_r($data);
    echo '</pre>';
});

Route::get('input/test', function(){
    //Input::flash();
    return Redirect::to('input/test2')->withInput(Input::only('user'));
});

Route::get('input/test2', function(){
    $data = Input::old();

    echo '<pre>';
    print_r($data);
    echo '</pre>';

    return "Hello Laravel Input";
});

Route::get('upload/form', function(){
    return View::make('form_upload');
});

Route::post('upload/process', function(){
    $data = Input::all();
//    echo '<pre>';
//    print_r($data);
//    echo '</pre>';
//
//    echo 'Size : ' .$data->getSize() . '<br/>';
//    echo 'Size : ' .$data->getRealPath() . '<br/>';
//    echo 'Size : ' .$data->getClientOriginalName() . '<br/>';
//    echo 'MimeType : ' .$data->getMimeType() . '<br/>';
//    echo 'Real path : ' .$data->getRealPath() . '<br/>';

    $rules = array(
        'img' => 'image|min:10'
    );

    $valid = Validator::make($data, $rules);

    if ($valid->passes()) {
        $image = $data['img'];
        $isUpload = $image->move('uploads/img', $image->getClientOriginalName());

        if ($isUpload) {
            return Response::json('success', 200);
        } else {
            return Response::json('error', 400);
        }
    } else {
        return Redirect::to('upload/form')->withErrors($valid);
    }

});

Route::get('upload/multi', function(){
	return View::make('upload_multi');
});

Route::any('upload/proccess_multi', array('as' => 'upload.multi',function(){

	if (Input::old('number') != '') {
		$number = Input::old('number');
	} else {
		$number = Input::get('number');
	}

	return View::make('process_multi', array(
		'number' => $number
	));
}));

Route::post('upload/do_upload', function(){
	$data = Input::all();

	$rules = array();

    $images = array();

	for ($i =0; $i < $data['number']; $i++) {
		$rules['fimage' . $i] = 'image|min:10|mimes:jpeg,jpg,png';
        $images[] = $data['fimage' . $i];
	}

	$validator = Validator::make($data, $rules);

	if ($validator->passes()) {
		$result = array();
		foreach ($images as $key => $image) {

            $isUpload = $image->move('uploads/img', $image->getClientOriginalName());

            if ($isUpload) {
                $result[] = 'success';
            } else {
                $result[] = 'failed';
            }

            unset($image);
        }

		return Response::json($result, 200);
	} else {
		return Redirect::to('upload/proccess_multi')->withInput(array(
			'number' => $data['number']
			))->withErrors($validator);
	}
});

Route::get('cookie/create', function(){
    $cookie = Cookie::make('name', 'jackie', 30);

    return Response::make('created cookie')->withCookie($cookie);
});

Route::get('cookie/show', function(){
    $name = Cookie::get('name');
    return 'Hello , ' . $name;
});

Route::get('library/sample01', function(){
    return View::make('library01');
});

Route::get('schema/create', function(){
    Schema::create('users', function($table){
        $table->increments('id');
        $table->string('username', 60);
        $table->string('password', 66);
        $table->integer('level')->default(1);
        $table->timestamps();
    });

    return 'Done';
});

Route::get('schema/rename', function(){
    Schema::rename('users', 'user');

    return 'Done';
});

Route::get('schema/change', function(){
    Schema::table('user', function($table){
        $table->string('email', 255)->after('password');
    });

    return 'Done';
});

Route::get('schema/column', function(){
    Schema::table('user', function($table){
        $table->renameColumn('email', 'mail');
    });

    return 'Done';
});

Route::get('schema/dropcolumn',function(){
    Schema::table('user', function($table){
        $table->dropColumn('mail');
    });

    return 'Done';
});

Route::get('schema/droptable', function(){
    Schema::drop('user');

    return 'Done';
});

Route::get('query/create', function(){
    DB::insert('insert into users(username, password, level) values(?,?,?)', array(
        'John',
        '5432108',
        '1'
    ));

    return 'Done';
});

Route::get('query/show', function(){
    $users = DB::select('select * from users where level = ?', array('1'));
    echo '<pre>';
    print_r($users);
    echo '</pre>';
});

Route::get('query/update', function(){
    DB::update('update users set username=? where id=?', array(
        'Peter123',
        '4'
    ));

    return 'Done';
});

Route::get('query/delete', function(){
    DB::delete('delete from users where id = ?', array(4));

    return 'Done';
});

Route::get('query2/all', function(){
    //$table = DB::table('users')->get(array('id, username'));
    //$table = DB::table('users')->select('id', 'username')->where('id', '>' , '1')->get();
//    $table = DB::table('users')->select('id', 'username')->where(function($where){
//        $where->where('id', '>', '2');
//        $where->where('level', '2');
//    })->get();

    $table = DB::table('users')->orderby('id', 'DESC')->skip(0)->take(5)->get();

    echo '<pre>';
    print_r($table);
    echo '</pre>';
});
