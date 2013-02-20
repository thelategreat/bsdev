<footer>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<p>Copyright &copy; <?=date('Y');?> &ndash; The Bookshelf</p>
		</div>
	</div>
</footer>
<!-- full skip link functionality in webkit browsers -->
<!-- <script src="../yaml/core/js/yaml-focusfix.js"></script> -->
<script src="/js/jquery-1.9.min.js"></script>
<script src="/js/layouts/main.js"></script>

<script src="/js/tooltipster-master/js/jquery.tooltipster.js"></script>
<script>
    $(document).ready(function() {
        $('.tooltip').tooltipster({
        	animation: 'grow',
        	delay: 50,
        	speed: 50,
        	position: 'top',
        	offsetX: 15,
        	arrowColor: '#0f0',
        	theme: 'tooltipster-punk'
        });
    });
</script>

</body>
</html>
