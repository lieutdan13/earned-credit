<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Earned Credit</title>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
    </head>
    <body ng-app="authApp">

        <div class="col-sm-12">
            <nav class="navbar navbar-default container">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active" ng-if="authenticated"><a href="#/attendees">Attendees <span class="sr-only">(current)</span></a></li>
                    </ul>
                    <div class="nav navbar-nav navbar-right" ng-if="authenticated">
                        <span class="navbar-text">Welcome, {{currentUser.name}}</span>
                        <span ng-controller="UserController as user">
                            <button class="btn btn-danger navbar-btn" ng-click="user.logout()">Logout</button>
                        </span>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="col-sm-12" ui-view></div>
    </body>

    <!-- Application Dependencies -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.5/angular.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.18/angular-ui-router.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/satellizer/0.14.0/satellizer.min.js"></script>

    <!-- Application Scripts -->
    <script src="scripts/app.js"></script>
    <script src="scripts/authController.js"></script>
    <script src="scripts/attendeeController.js"></script>
    <script src="scripts/userController.js"></script>
</html>
