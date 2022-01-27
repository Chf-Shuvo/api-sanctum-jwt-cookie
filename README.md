# Installation
    # git clone
    # create database 
    # import the sql file
# Documentation

## Create a new controller
    php artisan make:controller API/AuthController
## Modify the user table
    keep name, email, password only
## Migrate the tables
    php artisan migrate
## Modify the User Model
    Remove unnecessary fields
## Modify RouteServiceProvider if needed
    This is to remove prefix that shows 'api' in the URL. You can either change it to 'api/v.1/' or you can completely remove it. 
## Create routes is api.php
    See api.php for all the routes
## Create methods in AuthController
    See API/AuthController for all the methods
## Sending the first request through PostMan
    Post > URL > {{ base_path }}/{prefix}/url

    Postman > Body > raw + JSON
## When you add login system check the following things
    # If you are doing it with laravel 8 or later "Sanctum" is already installed and you have the "personal_access_token" table in your database. 

    # If you are using laravel<8 then install sanctum. 

    # User Model has that HasApiTokens traits enabled, if not then enable it
## If the user is not unauthenticated
    > return a response with Response Code
    > check the AuthController : Response::HTTP_UNAUTHORIZED is a Response library which lets you use direct named response instead of codes[201,200,401].
## Create token if authenticated
    $token = $user->createToken('jwt')->plainTextToken;
## Undefined Method createToken()
    This happens if you have installed PHP Intelliphense/Intellisense VsCode. To solve this problem install this IDE Helper Package:

    >https://github.com/barryvdh/laravel-ide-helper#automatic-PHPDocs-for-models
## Creating "Cookies"
    We are not storing the "Token" in the frontEnd, so we are creating "cookies" to store the "JWT" tokens in the session. 
## Create a cookie
    cookie('name',$value,time);
## Now we will send the cookie in the session as a response
    return response([
        'message' => 'success'
    ])->withCookie($cookie);
## If you are using a frontEnd then set cors.php
    'supports_credentials' => true
## Retrieve the logged user data
    > create user route in api.php
    > create method to retrieve user
    > add middleware to routes that are protected ['auth:sanctum]
## to get better validation message
    > in Postman add this header and value
    > X-Requested-With : XMLHttpRequest
## Usage of the "Token"
    We can use that token by returning it via the response. Then in the postman 
    > Authorization: Bearer $token

    But we are not giving the user the token so that it gets stored in the frontEnd. What we will do is, we will fake it so that the token gets shared in all upcoming requests after logging in. 
# Faking the $token 
##  Overwrite the "handle" function from "Authenticate.php" from "Vendor" to "Middleware"
### From this 
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }
### To this
    use Closure;
    public function handle($request, Closure $next, ...$guards)
    {
        // we are retrieving the jwt token from the cookie and adding it to the header as Authorization Bearer
        if($jwt = $request->cookie('jwt')){
            $request->headers->set('Authorization','Bearer '.$jwt);
        }
        $this->authenticate($request, $guards);

        return $next($request);
    }
## Logout
    > Create a logout route as post route

    public function logout(){
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }
## Reference Video
    https://www.youtube.com/watch?v=jIzPuM76-nI&ab_channel=ScalableScripts






