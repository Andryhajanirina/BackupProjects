<?php
	function createBodyOfMail($header, $message, $footer, $link = null, $logo = null)
	{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="http://192.168.88.166/assets/css/styles_email.css" media="all" rel="stylesheet" type="text/css" />
		</head>

		<body itemscope itemtype="http://schema.org/EmailMessage">

		<table class="body-wrap">
			<tr>
				<td></td>
				<td class="container" width="600">
					<div class="content">
						<table class="main" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="alert alert-warning">
									<?php echo $header; ?>
								</td>
							</tr>
							<tr>
								<td class="content-wrap">
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="content-block">
												<?php echo $message; ?>
											</td>
										</tr>
										<tr>
											<td class="content-block">
												<?php echo $link; ?>
											</td>
										</tr>
										<tr>
											<td class="content-block">
												<?php echo $footer; ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<div class="footer">
							<table width="100%">
								<tr>
									<td class="aligncenter content-block"><a href="http://www.mailgun.com">Unsubscribe</a> from these alerts.</td>
								</tr>
							</table>
						</div></div>
				</td>
				<td></td>
			</tr>
		</table>

		</body>
		</html>
		<?php
	}