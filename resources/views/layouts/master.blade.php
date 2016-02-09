<html>
	<head>
		{!! Html::style('/css/bootstrap-theme.min.css')!!}
		{!! Html::style('/css/bootstrap.min.css')!!}
		{!! Html::style('/css/layouts.css')!!}

		{!! Html::script('/js/jquery-2.2.0.js') !!}
		{!! Html::script('/js/bootstrap.min.js') !!}
		{!! Html::script('/js/layouts.js') !!}

		<!--[if lt IE 9]>
		{{ Html::style('https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js') }}
		{{ Html::style('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') }}
		<![endif]-->

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Tennis Club Chavornay</title>

	</head>

	<body>
		@section('menu')
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">TCC</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="#">Link <span class="sr-only">(current)</span></a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
					</ul>

					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="#">S'inscrire</a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Login <span class="caret"></span></a>
							<ul class="dropdown-menu"  style="padding: 15px;min-width: 250px;">
								<li>
									<div class="row">
										<div class="col-md-12">
											<form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
												<div class="form-group">
													<label class="sr-only" for="exampleInputEmail2">Email</label>
													<input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" required>
												</div>
												<div class="form-group">
													<label class="sr-only" for="exampleInputPassword2">Mot de passe</label>
													<input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox">
														Se rappeler de moi </label>
												</div>
												<div class="form-group">
													<button type="submit" class="btn btn-success btn-block">
														Connexion
													</button>
												</div>
											</form>
										</div>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		@show

		<div class="container">
			@yield('content')
		</div>

		<div id="footer">
			<div class="container text-center">
				<hr />
				<div class="row">
					<div class="col-lg-12">
						<div class="col-md-3">
							<ul class="nav nav-pills nav-stacked">
								<li>
									<a href="#">About us</a>
								</li>
								<li>
									<a href="#">Blog</a>
								</li>
							</ul>
						</div>
						<div class="col-md-3">
							<ul class="nav nav-pills nav-stacked">
								<li>
									<a href="#">Product for Mac</a>
								</li>
								<li>
									<a href="#">Product for Windows</a>
								</li>
							</ul>
						</div>
						<div class="col-md-3">
							<ul class="nav nav-pills nav-stacked">
								<li>
									<a href="#">Web analytics</a>
								</li>
								<li>
									<a href="#">Presentations</a>
								</li>
							</ul>
						</div>
						<div class="col-md-3">
							<ul class="nav nav-pills nav-stacked">
								<li>
									<a href="#">Product Help</a>
								</li>
								<li>
									<a href="#">Developer API</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-lg-12">
						<ul class="nav nav-pills nav-justified">
							<li>
								<a href="/">© 2013 Company Name.</a>
							</li>
							<li>
								<a href="#">Terms of Service</a>
							</li>
							<li>
								<a href="#">Privacy</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>