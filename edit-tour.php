<html>

<head>
    <title>Edit Tour</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- JavaScript from Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <script src="https://use.fontawesome.com/08847fc84c.js"></script>
    <!-- CSS from Bootstrap -->
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">

    <!-- Individualised CSS -->
    <link rel="stylesheet" type="text/css" href="style/style.css?">
</head>

<body id="UpdateTour">
    <?php include 'header.php'; ?>

    <!-- Edit Location Field -->
    <h1 class="text-center mt-3">Edit Tour</h1>

    <div class="container">
        <form action="" method="POST">
            <input type="hidden" id="id" name="id">
            <div id="TourInfo" class="">
                <div class="form-group row">
                    <label for="LocationName" class="col-sm-2 col-form-label">Tour Name</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="TournName" name="TourName" placeholder="Tour Name" value="">
                        <p class="text-danger">
                        </p>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="Coordinate" class="col-sm-2 col-form-label">Tour Type</label>
                    <div class="col-sm-4">
                        <select class="form-control"></select>
                        <option value=""></option>
                        <p class="text-danger"> 
                        </p>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:60%">Location</th>
                            <th>Min Duration</th>
                            <th style="width:10%"><button type="button" class="btn btn-default"><i class="fa fa-plus"></i></button></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <select class="form-control">
                                    <option value="">
                                    </option>
                                </select>
                            </td>
                            <td>
                            </td>
                            <td>
                            <button type="button" class="btn btn-default" onclick=""><i class="fa fa-close"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <select class="form-control">
                                    <option value="">
                                    </option>
                                </select>
                            </td>
                            <td>
                            </td>
                            <td>
                            <button type="button" class="btn btn-default" onclick=""><i class="fa fa-close"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <select class="form-control">
                                    <option value="">
                                    </option>
                                </select>
                            </td>
                            <td>
                            </td>
                            <td>
                            <button type="button" class="btn btn-default" onclick=""><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
               
                <div class="col-sm-4 pull pull-right">
                    <div class="form-group">
                    <label>(Total) Min. Duration</label>
                    <input></input>
                    </div>

                    
                </div>

            </div>
                

              
            <button id="UpdateButton" type="submit" class="btn btn-primary btn-block">Save Changes</button>
        
        </form>
    </div>

    <!-- Edit Location Field -->

    <!-- JS Retrieve Tour Info -->

    <!-- JS Retrieve Tour Info -->

    <?php include 'footer.php'; ?>
</body>

</html>