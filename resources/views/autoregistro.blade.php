<!DOCTYPE html>
<html lang="en">
<head>
 
</head>
<body>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
-->
<div class="container">
    <div class="col-md-12">
    <br>
    <br>
        <h2>Formulario de Autoregistro</h2>
        <!--Necesita de esto para las imagenes: enctype="multipart/form-data"-->
        <form action="{{ action([App\Http\Controllers\JugadorController:: class, 'autoregistro']) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" class="form-control" name="nombre" value=" ">
            </div>

            <div class="form-group">
                <label for="edad">Edad</label>
                <input type="text" class="form-control" name="edad" value=" ">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" value=" ">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" value=" ">
            </div>
            <input name="operacion" class="btn btn-success" value="Registrate" type="submit">
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!--<script src="{{ asset('public/jquery.min.js') }}"></script>
<script src="{{ asset('public/bootstrap.min.js') }}"></script>
-->
</body>
</html>