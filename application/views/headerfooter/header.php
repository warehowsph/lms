<!DOCTYPE html>
<html>
<!-- <style>
table, th, td {
  border:1px solid black;
}
</style> -->
<head>
  <meta charset="utf-8">
  
<!-- Styles -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto auto auto auto auto;
  grid-gap: 10px;
  background-color: #2196F3;
  padding: 10px;
}

.grid-container > div {
  background-color: rgba(255, 255, 255, 0.8);
  text-align: center;
  padding: 20px 0;
  font-size: 30px;
}


.center-block {
  margin: auto;
  display: block;
}

#div1 {
  border: 1px solid black;
  border-bottom: none;
}

#div2 {
  border: 1px solid black;
  border-top: none;
}

#div3 {
  border: 1px solid black;
  border-top: none;
  border-bottom: none;
  border-left: none;
  border-right: none;
}

#div4 {
  border: 1px solid black;
  border-top: none;  
  border-left: none;
  border-right: none;
}

#div-cell-no-bottom {
  border: 1px solid black;
  border-bottom: none;
}

#div-cell-only-top {
  border: 1px solid black;
  border-bottom: none;
  border-left: none;
  border-right: none;
}

input[type="text"] {
  border-top-style: hidden;
  border-right-style: hidden;
  border-left-style: hidden;
  border-bottom-style: groove;
  width: 50;
  text-align: center; 
  background-color: #eee;
}

/* .item1 {
  grid-column: 1 / 4;
} */
</style>
<!-- <link href="asset/css/bootstrap.min.css" rel="stylesheet">
<link href="asset/css/plugins/morris.css" rel="stylesheet">
<link href="asset/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="asset/css/styles.css" rel="stylesheet"> -->
<!-- Scripts -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</head>
<body>
<div class="container">