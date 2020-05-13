<!DOCTYPE html>
<html lang="en">
    <head>    
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> @yield('title') </title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    
    <body>

        <nav> 
            @yield('nav')
        </nav>  

        <div class="header">
            @yield('header')
        </div>  

        <div class="container">
            @yield('container')
        </div>           

        <div class="footer">
            @yield('footer')
        </div>   

       
         
        
    </body>
</html>