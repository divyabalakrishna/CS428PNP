<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

	<!-- Do not move from footer otherwise the home page will break -->
	<script src="<?php echo URL; ?>public/js/bootstrap.min.js"></script>
	<script src="<?php echo URL; ?>public/js/bootstrap-datepicker.js"></script>
	<script src="<?php echo URL; ?>public/js/bootstrap-timepicker.js"></script>
	<script src="<?php echo URL; ?>public/js/jquery.easing.min.js"></script>
	<script src="<?php echo URL; ?>public/js/jquery.fittext.js"></script>
	<script src="<?php echo URL; ?>public/js/wow.min.js"></script>
	<script src="<?php echo URL; ?>public/js/creative.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#notificationLink").click(function() {
                $("#notificationContainer").fadeToggle(300);
                $("#notification_count").fadeOut("slow");
                return false;
            });

            //Document Click
            $(document).click(function() {
                $("#notificationContainer").hide();
            });
            //Popup Click
//            $("#notificationContainer").click(function() {
//                return false
//            });

        });
    </script>

</body>
</html>