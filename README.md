<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">


| Laravel Version | PHP Version | 
| -------- | -------- | 
| 8.83.23     | 7.4.22| 


</p>

## Microsite con integración a PlaceToPay

Es un micrositio donde se pueden realizar las compras de productos y pagarlas a través de la pasarela de pagos PlaceToPay, el cual tiene integrada la mayoría de los medios en una solo plataforma.


## How to

* git clone https://github.com/kekosoftware/testing-placetopay.git
* composer install
* configure .env con las variables de la base de datos
* configure .env  con las variables del apartado Place to pay
* php artisan key:generate
* php artisan migrate
* php artisan serve
* http://127.0.0.1:8000

## Funcionamiento
1. Pantalla inicial: listado de productos que puede elegir para comprar y generar una orden de compra
2. Pantalla para generar la orden de compra, en el cual tiene que rellenar con sus datos y proceder al pago, que lo llevará a sitio de Place to pay.
3. Completar los datos en el sitio de Place to pay y una vez finalizado, lo regresará al sitio desde donde inició.
4. Un listado de ordenes de comprar con la opción de ver un detalla de cada orden de compra
5. Una pantalla con el detalle  de la orden de comprar.


## License
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
This API is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
