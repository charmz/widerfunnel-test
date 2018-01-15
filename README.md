# Laravel on AWS

A quick example to illustrate the use of Laravel and Vue.js


## Bash commands for Ubuntu 16.04

Set environment (mysql password is password)

```
sudo apt-get update
sudo apt-get install apache2
sudo apt-get install mysql-server
sudo apt-get install php libapache2-mod-php php-mcrypt php-mysql php-mbstring php-zip php-xml
sudo a2enmod rewrite
```


Get Composer

```
cd ~/
curl -O https://getcomposer.org/composer.phar
mv composer.phar composer
chmod +x composer
sudo mv composer /usr/local/bin
```

Temporary Permissions

```
sudo chown -R www-data:www-data /var/www/html
sudo usermod -a -G www-data ubuntu
sudo chmod -R 777 /var/www
```

Install Laravel


```
rm /var/www/html/index.html
git clone https://github.com/charmz/noter.git /var/www/html
cd /var/www/html
composer update
mv .env.example .env
php artisan key:generate
```

Recommended Permissions (not working)

```
sudo find /var/www/html -type f -exec chmod 644 {} \;
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo chown -R www-data:www-data /var/www/html
sudo find /var/www/html -type f -exec chmod 664 {} \;
sudo find /var/www/html -type d -exec chmod 775 {} \;
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

Set up VIM

```
mkdir -p ~/.vim/autoload ~/.vim/bundle && \
curl -LSso ~/.vim/autoload/pathogen.vim https://tpo.pe/pathogen.vim

cd ~/.vim/bundle && \
git clone https://github.com/posva/vim-vue.git


cd ~/.vim/bundle
git clone git://github.com/jwalton512/vim-blade.git

cd ~/.vim/bundle
git clone https://github.com/sjl/badwolf.git
```

vim ~/.vimrc

```
execute pathogen#infect()
syntax on
filetype plugin indent on
colorscheme badwolf         " awesome colorscheme
set tabstop=4       " number of visual spaces per TAB
set shiftwidth=4
set softtabstop=4   " number of spaces in tab when editing
set expandtab       " tabs are spaces
set showcmd             " show command in bottom bar
set cursorline          " highlight current line
set wildmenu            " visual autocomplete for command menu
set lazyredraw          " redraw only when we need to.
set showmatch           " highlight matching [{()}]
set incsearch           " search as characters are entered
set hlsearch            " highlight matches
" turn off search highlight
nnoremap <leader><space> :nohlsearch<CR>
set foldenable          " enable folding
set foldlevelstart=10   " open most folds by default
set foldnestmax=10      " 10 nested fold max
" space open/closes folds
nnoremap <space> za
set foldmethod=indent   " fold based on indent level

```

Update your apache conf file

```
sudo vim /etc/apache2/sites-enabled/000-default.conf
```

```
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    <Directory /var/www/html>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
    </Directory>
</VirtualHost>
```
Restart apache

```
sudo apachectl -k restart
```

Create database


```
mysql -uroot -p
```

Enter your root  password and within mysql command line

```
create database noter;
exit
```

Configure database in environment file

```
vim /var/www/html/.env
```

Update the following:

```
DB_DATABASE=noter
DB_USERNAME=root
DB_PASSWORD=password
```

create model with (m)igration and (c)ontroller flags:

```
cd /var/www/html

php migrate

php artisan make:model Note -mc
```

Install node and Vue


```
cd ~
curl -sL https://deb.nodesource.com/setup_6.x -o nodesource_setup.sh
sudo bash nodesource_setup.sh
sudo apt-get install nodejs
sudo apt-get install build-essential

```

Now we edit the model

```
vim /var/www/html/app/Note.php
```

to:

```
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
}

```

The migration

```
vim /database/migration/[timestamp]_notes_table.php
```

```
public function up()
{
    Schema::create('notes', function (Blueprint $table) {
        $table->increments('id');
        $table->text('body');
        $table->softDeletes();
        $table->timestamps();
    });
}
```

```
php artisan migrate
```






HERE WE NEED TO LOOK AT AUTH

```
npm install -g vue-cli
vue init webpack-simple resources
cd resources
npm i
npm i vue-router --save-dev
mkdir assets/components
```

```
vim assets/components/App.vue
```

```
<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <nav>
                <ul class="list-inline">
                    <li>
                        <router-link :to="{ name: 'home' }">Home</router-link>
                    </li>
                    <li class="pull-right">
                        <router-link :to="{ name: 'register' }">Register</router-link>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="panel-body">
            <router-view></router-view>
        </div>
    </div>
</template>
```

```
vim assets/components/Home.vue
```

```
<template>
    <h1>Laravel 5</h1>
</template>
```

```
vim assets/components/Register.vue
```

```
<template>
    <div>
        <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
        </div>
        <div class="alert alert-success" v-if="success">
            <p>Registration completed. You can now sign in.</p>
        </div>
        <form autocomplete="off" v-on:submit="register" v-if="!success">
            <div class="form-group" v-bind:class="{ 'has-error': error && response.username }">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="name" required>
                <span class="help-block" v-if="error && response.name">{{ response.name }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && response.email }">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="gavin.belson@hooli.com" v-model="email" required>
                <span class="help-block" v-if="error && response.email">{{ response.email }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && response.password }">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
                <span class="help-block" v-if="error && response.password">{{ response.password }}</span>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</template>

<script>
import auth from '../js/auth.js';

export default {
    data() {
        return {
            name: null,
            email: null,
            password: null,
            success: false,
            error: false,
            response: null
        }
    },
    methods: {
        register(event) {
            event.preventDefault()
            auth.register(this, this.name, this.email, this.password)
        }
    }
}
</script>
```

```
vim assets/components/Signin.vue
```

```
<template>
    <div>
        <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
        <form autocomplete="off" v-on:submit="signin">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="gavin.belson@hooli.com" v-model="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
            </div>
            <button type="submit" class="btn btn-default">Sign in</button>
        </form>
    </div>
</template>
<script>
import auth from '../js/auth.js';

export default {
    data() {
            return {
                email: null,
                password: null,
                error: false
            }
        },
        methods: {
            signin(event) {
                event.preventDefault()
                auth.signin(this, this.email, this.password)
            }
        }
}
</script>
```

```
vim assets/components/Dashboard.vue
```

```
<template>
    <h1>Laravel 5 - Dashboard</h1>
</template>
```

```
cd /var/www/html
sudo npm install
sudo npm install bulma -S
sudo npm install node-sass
```

add to .gitignore if you use VIM

```
.swp
```

## Acknowledgments

[bgies - Laravel Ubuntu](https://laracasts.com/discuss/channels/general-discussion/laravel-framework-file-permission-security?page=1)

[Vijaya Sankar N - Laravel AWS](https://vijayasankarn.wordpress.com/2017/01/17/installing-laravel-framework-on-ubuntu-16-04-using-aws-ec2/)

[Doug Black - VIMRC](https://dougblack.io/words/a-good-vimrc.html)

[Pathogen](https://github.com/tpope/vim-pathogen)

[Jason Walton - Vim PHP Blade](https://github.com/jwalton512/vim-blade)

[Badwolf](https://github.com/sjl/badwolf/)

[fl2top Laravel and Vue](https://codeburst.io/simple-to-do-application-with-laravel-and-vue-4af28cb007ad)

[Jim Frenette - Laravel JWT Auth with Vue.js 2](http://jimfrenette.com/2016/11/laravel-vuejs2-jwt-auth/)

[mzarate Solution Factor](https://solutionfactor.net/blog/2017/11/15/laravel-5-5-jwt-authentication-example/)

[mtpultz](https://github.com/tymondesigns/jwt-auth/issues/860)

[Ken](https://stackoverflow.com/questions/234564/tab-key-4-spaces-and-auto-indent-after-curly-braces-in-vim)



