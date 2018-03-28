# paperShop
基于Laravel5.5 的小程序开发


### 部分页面效果展示

![image.png](https://upload-images.jianshu.io/upload_images/3769899-b12dcb146eb3f5ae.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

![image.png](https://upload-images.jianshu.io/upload_images/3769899-b47b49fa99bf1a82.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

![image.png](https://upload-images.jianshu.io/upload_images/3769899-e51a6e230e056c04.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

![image.png](https://upload-images.jianshu.io/upload_images/3769899-4b591a89d1effc97.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

![image.png](https://upload-images.jianshu.io/upload_images/3769899-2c2d26fd9b59bfb8.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

![image.png](https://upload-images.jianshu.io/upload_images/3769899-ae51d2bcf3258452.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

.....

> 为了让团队开发更加顺利，这里对一些基础功能统一做了封装，让大家直接使用。避免重复开发、提高效率，也是做了一个统一规范。

## 公共部分

指所有模块，包含后台，前台、api等

#### 1、数据model
统一放在 app目录下的Models文件夹中
#### 1、逻辑logic
统一放在 app目录下的Logics文件夹中


---

## Api部分

#### 1、封装返回的统一消息

返回的自定义消息，和错误消息，封装了一个Trait，用来做基本的返回，如下

```
namespace App\Api\Helpers\Api;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Response;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {

        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data, $header = [])
    {

        return Response::json($data,$this->getStatusCode(),$header);
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @return mixed
     */
    public function status($status, array $data, $code = null){

        if ($code){
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code' => $this->statusCode
        ];

        $data = array_merge($status,$data);
        return $this->respond($data);

    }

    /**
     * @param $message
     * @param int $code
     * @param string $status
     * @return mixed
     */
    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST, $status = 'error'){

        return $this->setStatusCode($code)->message($message,$status);
    }

    /**
     * @param $message
     * @param string $status
     * @return mixed
     */
    public function message($message, $status = "success"){

        return $this->status($status,[
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!"){

        return $this->failed($message,FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created")
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);

    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data, $status = "success"){

        return $this->status($status,compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = 'Not Fond!')
    {
        return $this->failed($message,Foundationresponse::HTTP_NOT_FOUND);
    }

}
```
然后创建一个ApiController,通过**所有的Api控制器继承该控制器**,实现简洁的Api返回  ApiController代码：


```
<?php

namespace App\Http\Controllers\Api;

use App\Api\Helpers\Api\ApiResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    use ApiResponse;

    // 其他通用的Api帮助函数

}
```

然后，大家的Api控制器就可以简洁的返回

```
<?php

namespace App\Http\Controllers\Api;

class IndexController extends ApiController
{
    public function index(){

        return $this->message('请求成功');
    }
}
```

#### 2、使用资源类型的返回

资源返回通过5.5的新特性,API资源实现,具体参见 https://d.laravel-china.org/docs/5.5/eloquent-resources

比如返回用户的**分页数据**，只需要这样

其他功能看上面链接的文档

```
<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Resources\User as UserCollection;
use Illuminate\Support\Facades\Input;

class IndexController extends ApiController
{
    public function index(){

        return UserCollection::collection(User::paginate(Input::get('limit') ?: 20));

    }
}
```


未完待续.....
