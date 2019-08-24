<!doctype html>
<html lang="en">
<body>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <title>Hello, world!</title>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Football Teams</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="#">Schedule</a>
                <a class="nav-item nav-link" href="#">League</a>
                <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">About</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">

    <div class="row mt-2 mb-2">
        <div class="col-lg-6"><h4>List All Team</h4></div>
        <div class="col-lg-6">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">Select League</label>
                </div>
                <select class="custom-select" id="league"></select>
            </div>
        </div>
    </div>

    <div class="row" id="loader">
        <img class="mx-auto" src="img/loading.gif" alt="" style="width: 100px">
    </div>

    <div class="row mx-auto" id="data"></div>

</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>


    $(document).ready(function () {
        let teams = null;
        //panggil pertama
        loadLeague();
        loadTeams(4328);

        function loadLeague() {
            $.ajax({
                url     : 'https://www.thesportsdb.com/api/v1/json/1/all_leagues.php',
                method  : 'GET',
                success : function (result) {
                    loadDataLeague(result);
                },
                error   : function (err) {
                    console.log(err);
                }
            });
        }

        function loadTeams(id) {
            $.ajax({
                url     : "https://www.thesportsdb.com/api/v1/json/1/lookup_all_teams.php?id=" + id,
                method  : 'GET',
                success : function (result) {
                    teams = result.teams;
                    loadData(result);
                },
                error   : function (err) {
                    console.log(err);
                }
            });
        }

        function loadDataLeague(result) {
            $.each(result.leagues, function (index, item) {
                $('#league').append(`
                    <option value=" `+ item.idLeague +` ">
                        `+ item.strLeague +`
                    </option>
                `);
            });

            $('#league').change(function () {
                $('#data').html('');
                $('#loader').show();

                let idLeague = $(this).val();
                loadTeams(idLeague);
            });
        }

        function loadData(result) {
            $('#loader').hide();
            $.each(result.teams, function (index, item) {

                $('#data').append(`
                    <div class="col-lg-3 col-sm-12">
                        <div class="card mb-4 mr-2 col-lg-12">
                            <img src=" `+ item.strTeamBadge +` "
                                 class="card-img-top mt-2" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">`+ item.strTeam +`</h5>
                                <p class="card-text">`+ item.strKeywords +`</p>
                                <button data-index="`+ index +`"
                                class="btn btn-primary viewDetail">View Detail</button>
                            </div>
                        </div>
                    </div>
                `);
            });

            $('.viewDetail').click(function () {
                let index = $(this).data('index');
                let data = teams[index];
                viewDetail(data);
            });
        }

        function viewDetail(data) {
            $('#team').html(data.strTeam);
            $('#description').html(data.strDescriptionEN);
            $('#badge').attr('src', data.strTeamBadge);
            $('#year').html(data.intFormedYear);
            $('#detail').modal('show');
        }

    });



</script>


</body>
</html>


<!--Modal-->
<!-- Modal -->
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-4 mt-4">
                            <img id="badge" src="" class="card-img" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title" id="team"></h5>
                                <p class="card-text" id="description"></p>
                                <p class="card-text">
                                    <small class="text-muted">Since
                                        <span id="year"></span>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!---->