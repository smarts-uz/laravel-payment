# Listeners

With the installation of Package you will find a new configuration files located at `app/Http/Payments/`.  
In these files you can define different custom methods in that hooks.

Listeners list:

* before-pay - the code will work inside this file before starting pay process
* paying - the code will work inside this file while paying process
* after-pay - the code will work inside this file after success pay
* cancel-pay - the code will work inside this file after you cancel the success pay
