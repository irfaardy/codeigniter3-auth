# Codeigniter 3  Auth
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/irfaardy/codeigniter3-auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/irfaardy/codeigniter3-auth/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/irfaardy/codeigniter3-auth/badges/build.png?b=master)](https://scrutinizer-ci.com/g/irfaardy/codeigniter3-auth/build-status/master)
<h3>Simple Authentication Library for Codeigniter 3</h3>

**Setup** 

1. Copy this package to root project.
2. Add auth library in ``config/autoload.php``.

```php
...
$autoload['libraries'] = array('auth');
...
```

**Verify username and password**

```php
...
if($this->auth->verify('username','password123')) {
		echo "Login Success";
} else {
		echo"Login Fail";
}
...
```

**Check user is Authenticated**

```php
...
if($this->auth->check()) {
		echo "User is logged in";
} else {
		echo"User not Logged in";
}
...
```

**Get User data Logged in User** 

```php
...
$user = $this->auth->user();
echo $user->id;
echo $user->name;
...
```

**Protect Controller**

```php
$this->auth->protect();
//All logged in user can acces this controller
$this->auth->protect([4,5]);
//Only Level  4 or 5 can access this controller
$this->auth->protect(3);
//Only level 3 can access this controller

```

**Example**

```php
class Dashboard extends CI_Controller 
    function __construct(){
        parent::__construct();
		$this->auth->protect();
    }
    public function index()
    {
		$this->load->template('dashboard/index');
    }
}
```

**Logout**

```php
$this->auth->logout();
```

License
