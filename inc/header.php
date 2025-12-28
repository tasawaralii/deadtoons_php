<?php
require_once dirname(__DIR__) . '/db.php';
require_once dirname(__DIR__) . '/functions.php';
$menuData = get_menu_data_cached($pdo);
?>
<header id="header" class="herald-site-header">
	<div class="header-middle herald-header-wraper hidden-xs hidden-sm">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 hel-el">
					<div class="hel-l herald-go-hor">
						<div class="site-branding">
							<h1 class="site-title h1"><a href="/" rel="home"><img class="herald-logo no-lazyload"
										src="/public/images/logo.png"
										alt="Dead Toons India - Anime and Cartoon Videos in Hindi Download"></a></h1>
						</div>
					</div>
					<div class="hel-c herald-go-hor">
						<nav class="main-navigation herald-menu">
							<?php echo build_menu_html($menuData, 'menu-header', 'menu'); ?>
						</nav>
					</div>
					<div class="hel-r herald-go-hor">
						<div class="herald-menu-popup-search">
							<span class="fa fa-search"></span>
							<div class="herald-in-popup">
								<form class="herald-search-form" action="/" method="get">
									<input name="s" class="herald-search-input" type="text" value=""
										placeholder="Type here to search..." /><button type="submit"
										class="herald-search-submit"></button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>


<div id="sticky-header" class="herald-header-sticky herald-header-wraper herald-slide hidden-xs hidden-sm">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 hel-el">
				<div class="hel-l herald-go-hor">
					<div class="site-branding mini">
						<span class="site-title h1">
							<a href="/" rel="home">
								<img class="herald-logo-mini no-lazyload" src="/public/images/logo.png"
									alt="Dead Toons India - Anime and Cartoon Videos in Hindi Download">
							</a>
						</span>
					</div>
				</div>


				<div class="hel-r herald-go-hor">
					<nav class="main-navigation herald-menu">
						<?php echo build_menu_html($menuData, 'menu-header-1', 'menu'); ?>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="herald-responsive-header" class="herald-responsive-header herald-slide hidden-lg hidden-md">
	<div class="container">
		<div class="herald-nav-toggle"><i class="fa fa-bars"></i></div>
		<div class="site-branding mini">
			<span class="site-title h1"><a href="/" rel="home"><img class="herald-logo-mini no-lazyload"
						src="/public/images/logo.png"
						alt="Dead Toons India - Anime and Cartoon Videos in Hindi Download"></a></span>
		</div>
		<div class="herald-menu-popup-search">
			<span class="fa fa-search"></span>
			<div class="herald-in-popup">
				<form class="herald-search-form" action="/" method="get">
					<input name="s" class="herald-search-input" type="text" value=""
						placeholder="Type here to search..." /><button type="submit"
						class="herald-search-submit"></button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="herald-mobile-nav herald-slide hidden-lg hidden-md">
	    <?php echo build_menu_html($menuData, 'menu-header-2', 'herald-mob-nav'); ?>
</div>