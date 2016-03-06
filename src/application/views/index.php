<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<img class="icon" src="<?php echo URL; ?>public/img/pnp.png" />
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="" data-toggle="modal" data-target="#myModal">Sign in</a>
				</li>
				<li>
					<a href="" data-toggle="modal" data-target="#myModal2">Sign Up</a>
				</li>
			</ul>
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container-fluid -->
</nav>

<!-- Modal Sign In -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="form-group" id="signinForm" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>user/login">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel"><img class="icon" src="<?php echo URL; ?>public/img/icon.png"> Sign In</h3>
				</div>
				<div class="modal-body">
					<?php echo $GLOBALS["beans"]->siteHelper->getAlertHTML(); ?>
					<label for="email" class="sr-only">Email address</label>
					<input type="email" id="email" name="email" class="form-control" placeholder="Email address" required>
					<br>
					<label for="password" class="sr-only">Password</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
<!--					<div class="checkbox">
						<label>
							<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>-->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Sign In</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Sign Up -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="form-group" id="signupForm" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>user/createAccount">
                <fieldset>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel"><img class="icon" src="<?php echo URL; ?>public/img/icon.png"> Sign Up - New User</h3>
				</div>
				<div class="modal-body">
					<label for="email" class="sr-only">Email address</label>
                    <input type="hidden" id="existingEmail" name="existingEmail" value="<?php echo $email ?>" />
					<input type="email" id="email" name="email" class="form-control" placeholder="Email address" required>
					<br>
					<label for="password1" class="sr-only">Password</label>
					<input type="password" id="password1" name="password1" class="form-control" placeholder="Password" required>
					<br>
					<label for="password2" class="sr-only">Re-type Password</label>
					<input type="password" id="password2" name="password2" class="form-control" placeholder="Re-type Password" required>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<!--				<button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>-->
					<button type="submit" class="btn btn-primary">Register</button>
				</div>
                </fieldset>
			</form>
		</div>
	</div>
</div>      

<header>
	<div class="header-content">
		<div class="header-content-inner">
			<h1>PLAN n PLAY</h1>
			<hr>
			<p>PLAN n PLAY helps you to better organize sports events and also helps you to join an event happening in your vicinity</p>
			<a href="#about" class="btn btn-primary btn-xl page-scroll">Find Out More</a>
		</div>
	</div>
</header>

<section class="bg-primary" id="about">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 text-center">
				<h2 class="section-heading">We've got what you need!</h2>
				<hr class="light">
				<p class="text-faded">PLAN n PLAY has everything you need to form groups and play a sport of your interest. All the events happening in the radius specified by you, is available for you to join along with screens to aid you to organize any sport of your interest!</p>
				<a href="#" class="btn btn-default btn-xl">Get Started!</a>
			</div>
		</div>
	</div>
</section>

<section id="services">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<h2 class="section-heading">At Your Service</h2>
				<hr class="primary">
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6 text-center">
				<div class="service-box">
					<i class="fa fa-4x fa-newspaper-o wow bounceIn text-primary" data-wow-delay=".2s"></i>
					<h3>Up to Date</h3>
					<p class="text-muted">Our screens are loaded with all the events in your vicinity based on your interests</p>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 text-center">
				<div class="service-box">
					<i class="fa fa-4x fa-paper-plane wow bounceIn text-primary" data-wow-delay=".1s"></i>
					<h3>Event Organizer</h3>
					<p class="text-muted">You can use this application to create events and let interested people join</p>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 text-center">
				<div class="service-box">
					<i class="fa fa-4x fa-heart wow bounceIn text-primary" data-wow-delay=".3s"></i>
					<h3>Make memories</h3>
					<p class="text-muted">You can store all the memories of your past events</p>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 text-center">
				<div class="service-box">
					<i class="fa fa-4x fa-clock-o wow bounceIn text-primary" data-wow-delay=".2s"></i>
					<h3>Change anytime</h3>
					<p class="text-muted">You can edit your interests anytime to see all the events on your feed</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="no-padding" id="portfolio">
	<div class="container-fluid">
		<div class="row no-gutter">
			<div class="col-lg-4 col-sm-6">
				<a href="#" class="portfolio-box">
					<img src="<?php echo URL; ?>public/img/portfolio/1.jpg" class="img-responsive" alt="">
				</a>
			</div>
			<div class="col-lg-4 col-sm-6">
				<a href="#" class="portfolio-box">
					<img src="<?php echo URL; ?>public/img/portfolio/2.jpg" class="img-responsive" alt="">
				</a>
			</div>
			<div class="col-lg-4 col-sm-6">
				<a href="#" class="portfolio-box">
					<img src="<?php echo URL; ?>public/img/portfolio/3.jpg" class="img-responsive" alt="">
				</a>
			</div>
			<div class="col-lg-4 col-sm-6">
				<a href="#" class="portfolio-box">
					<img src="<?php echo URL; ?>public/img/portfolio/4.jpg" class="img-responsive" alt="">
				</a>
			</div>
			<div class="col-lg-4 col-sm-6">
				<a href="#" class="portfolio-box">
					<img src="<?php echo URL; ?>public/img/portfolio/5.jpg" class="img-responsive" alt="">
				</a>
			</div>
			<div class="col-lg-4 col-sm-6">
				<a href="#" class="portfolio-box">
					<img src="<?php echo URL; ?>public/img/portfolio/6.jpg" class="img-responsive" alt="">
				</a>
			</div>
		</div>
	</div>
</section>

<section id="contact">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 text-center">
				<h2 class="section-heading"><a href="https://wiki.cites.illinois.edu/wiki/display/cs428sp16/PLAN+n+PLAY">project wiki</a></h2>
				<hr class="primary">
			</div>
		</div>
	</div>
</section>

<script>
	$(document).ready(function(){
		$('body').attr('id', 'page-top');
        
        <?php echo $GLOBALS["beans"]->siteHelper->getPopUp(); ?>
        
        // validation
		$("#signinForm").validate({
			rules: {
				email: {
                    required: true,
					email: true
                },
				password: {
					required: true
				}
			},
			messages: {
				email: "Please enter your email",
				password: {
					required: "Please provide a password"
				}
			}
		});
        
    
		$("#signupForm").validate({
            
			rules: {
				email: {
                    required: true,
                    email: true,
                    remote: {
                        depends: function(element) {
                            return $('#existingEmail').val() != $(element).val();
                        },
                        param: {
                            url: '<?php echo URL_WITH_INDEX_FILE; ?>user/checkUniqueEmail',
                            type: 'post'
                        }
                    }
                },
				password1: {
					required: true,
					minlength: 6
				},
				password2: {
					required: true,
					minlength: 6,
					equalTo: "#password1"
				}
			},
            
			messages: {
				email: {
                    required: "Please enter your email",
                    email: "Please enter correct email format",
                    remote: "There is an existing account with this email."
                },
				password1: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				},
				password2: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long",
					equalTo: "Please enter the same password as above"
				}
			}
		});
        
	});
</script>