<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<title>Invoice</title>
	<style>
		body {
			font-family: 'Open Sans', sans-serif;
			font-size: 14px;
		}

		#table_bg {
			width: 100%;
			background: #f5f6fa;
			height: 180px;
			position: relative;
			padding: 15px 0 10px 0;
		}

		ul.social_media {
			margin: 0 padding:0;
		}

		ul.social_media li {
			list-style: none;
			display: inline-block;
		}

		ul.footer-links {
			margin: 0;
			padding: 0;
		}

		ul.footer-links li {
			list-style: none;
			display: inline-block;
			vertical-align: top;
		}

		ul.footer-links li:last-child {
			float: right;
		}

		ol.social_media li {
			margin-right: 5px;
			position: relative;
			top: 5px;
		}

		ol.social_media li i {
			font-size: 16px;
			background: #f5f6fa;
			width: 30px;
			height: 30px;
			color: #333;
			text-align: center;
			line-height: 30px;
			border-radius: 50px;
		}

		#table_innerVoice {
			padding: 15px;
			border-radius: 6px;
		}
	</style>
</head>

<body style="margin: 0; padding: 0; background-color:#f2f2f2;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
		<tr>
			<td>
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color:#fff;">
					<tr>
						<td>
							<table id="table_bg">
								<tr>
									<td style="position:absolute;width: 100%;text-align: center;">
										<img src="{{asset('resources/assets/images/palkee_logo_invoice.png')}}" style="width:90px; padding:10px 0;" />
									</td>
								</tr>
							</table>

						</td>

					</tr>


					<tr>
						<td>
							<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color:#f5f5f5;">
								<tr>
									<td>
										<table id="table_innerVoice" align="center" border="0" cellpadding="0" width="550" cellspacing="0" style="border-collapse: collapse; background-color:#fff;    margin-bottom: 20px;">
											<tr>
												<td colspan="2">
													<p style="text-align: left;padding:15px;margin: 0;font-size: 18px;border-bottom: 1px solid #f5f5f5; text-align: center;"><strong>{{$full_name}}</strong></p>
												</td>
											</tr>

											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin:0;color: #68043a;"><strong>{{$model_name}}</strong></p>
													<span style="display: block;text-align: left;padding:0 15px 15px 15px">{{$vehicle_number}}</span>
												</td>

												<td>
													<p style="text-align: right;padding:7.5px 15px;margin: 0;color: #68043a;"><strong>Ride ID</strong></p>
													<span style="text-align: right;padding:0 15px 15px 15px;display: block;">{{$ride_id}}</span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>




					<tr>
						<td>
							<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color:#f5f5f5;">
								<tr>
									<td>
										<table id="table_innerVoice" align="center" border="0" cellpadding="0" width="550" cellspacing="0" style="border-collapse: collapse; background-color:#fff;    margin-bottom: 20px;">
											<tr>
												<td>
													<p style="text-align: left;padding:15px;margin: 0;"><strong>Ride Details</strong></p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 0;">
														<i style="color: #68043a;" class="fa fa-map-pin" aria-hidden="true"></i> <strong>{{ $start_location }}</strong>
													</p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 0;">
														<i style="color: #68043a;" class="fa fa-map-marker" aria-hidden="true"></i> <strong>{{ $end_location }}</strong>
													</p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 15px 0 0 0 ;color: #68043a;"><i class="fa fa-map-o" aria-hidden="true"></i> <strong>Total Distance</strong></p>
													<span style="display: block;text-align: left;padding:0 15px 15px 15px">{{$distance/1000}} KM</span>
												</td>

												<td>
													<p style="text-align: right;padding:7.5px 15px;margin: 0;color: #68043a;"><i class="fa fa-clock-o" aria-hidden="true"></i> <strong>Total Time</strong></p>
													<span style="text-align: right;padding:0 15px 15px 15px;display: block;">{{ $duration/60}}} Min</span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color:#f5f5f5;">
								<tr>
									<td>
										<table id="table_innerVoice" align="center" border="0" cellpadding="0" width="550" cellspacing="0" style="border-collapse: collapse; background-color:#fff;margin-bottom:30px;">
											<tr>
												<td>
													<p style="text-align: left;padding:15px;margin: 0;"><strong>Billing Details</strong></p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 0;">Ride Cost</p>
												</td>
												<td>
													<p style="text-align: right;padding:7.5px 15px;margin: 0;"><strong>TK {{$total_bill}}</strong></p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 0;color: #68043a;"><strong>Discount</strong></p>
												</td>
												<td>
													<p style="text-align: right;padding:7.5px 15px;margin: 0;color: #68043a;"><strong>TK {{$discount}}</strong></p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 0;">Sub Total</p>
												</td>
												<td>
													<p style="text-align: right;padding:7.5px 15px;margin: 0;"><strong>TK {{$total_bill - $discount }}</strong></p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 0;">Taxes & Charges</p>
												</td>
												<td>
													<p style="text-align: right;padding:7.5px 15px;margin: 0;"><strong>TK {{$tax}}</strong></p>
												</td>
											</tr>


											<tr>
												<td>
													<p style="text-align: left;padding:7.5px 15px;margin: 0;"><strong>Grand Total</strong></p>
												</td>
												<td>
													<p style="text-align: right;padding: 15px;margin: 0;"><strong>TK {{($total_bill - $discount)+$tax }}</strong></p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>


					<tr>
						<td style="border-top:1px solid #ddd; padding:10px 20px;">
							<ul class="footer-links">
								<li><img src="{{asset('resources/assets/images/logo-word.png')}}" style="width:125px;" /></li>
								<li>
									<ol class="social_media">
										<li><a href="#"><i class="fa fa-facebook"></i></a></li>
										<li><a href="#"><i class="fa fa-twitter"></i></a></li>
										<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
									</ol>
								</li>
							</ul>

						</td>
						<td>

						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

</html>