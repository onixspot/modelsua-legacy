<html>
	<head>
		<?=client_helper::get_meta()?>
		<?=client_helper::get_title()?>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="http://js.<?=conf::get("server")?>/main.js"></script>
		<script type="text/javascript" src="http://js.<?=conf::get("server")?>/forum.js"></script>
                <link href="http://css.<?=conf::get("server")?>/main.css" rel="stylesheet" />
		<link href="http://css.<?=conf::get("server")?>/forum.css" rel="stylesheet" />
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	</head> 
	<body style="overflow-x: hidden">
		<div id="opaco" class="hide"></div>
		<div class="canvas forum tahoma p5 pl20" style="min-height: 1010px; background: #fff;">
			
			<div style="position: absolute; width: 1801px; margin: 0px auto; top: 0px; margin-left: -398px; z-index: -2;">
				<div class="left" style="background: url(/left.png); width: 378px; height: 1138px;"></div>
				<div class="right" style="background: url(/right.png); width: 378px; height: 1138px;"></div>
				<div class="clear"></div>
			</div>
			
			<div
				style="position: absolute; cursor: pointer; width: 42px; height: 286px; background: url('/banners/UFW.png'); margin-left: 1025px; margin-top: 140px;"
				onclick="
					window.open('http://fashionweek.ua/');
				">
			</div>
			
			<div
				style="position: absolute; cursor: pointer; width: 42px; height: 115; background: url('/banners/elle.png'); margin-left: 1025px; margin-top: 456px;"
				onclick="
					window.open('http://elle.com.ua/');
				">
			</div>
			
			<script type="text/javascript">
				$(function() {
					var sw = screen.width;
					var ww = $(window).width();
					var dw = 1020;
					var zoom = sw * 100 / ww;
					
					
					dw = dw * zoom / 100;
					
					var offset = (ww - dw) / 2;
					
					if($.browser.msie || $.browser.safari)
					{
						$('.canvas, .footer_box').css('margin-left', (offset+'px'));
						//$(window).scrollLeft(ww/2);
						$('.footer').css('width', $(document).width()+'px');
					}
				});
				
				
				
			</script> 
			
			<script type="text/javascript">

				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', 'UA-30049596-1']);
				_gaq.push(['_setDomainName', 'modelsua.org']);
				_gaq.push(['_trackPageview']);

				(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();

			</script>
			
			<!-- START HEAD -->
			<? include "partials/logo.php" ?>
			<!-- END HEAD -->
			
			<div>
				<div class="content">
					<? include $controller->get_template_path(); ?>
				</div>
			</div>
			
		</div>
		<div class="footer tahoma">
			<?//include 'partials/footer.php'?>
		</div>
	</body>
</html>
