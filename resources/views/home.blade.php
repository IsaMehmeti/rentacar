@extends('layouts.app')
@section('page_name', __('Dashboard'))
@section('content')
<div class="container">
                <div class="row">

						<div class="col-lg-12">
							<div class="row mb-3">
                                <div class="col-xl-6">
									<section class="card card-featured-left card-featured-secondary">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-secondary">
														<i class="fas fa-dollar-sign"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Aktive Tani</h4>
														<div class="info">
															<strong class="amount">{{$registers->where('status', 0)->count()}}</strong>
														</div>
													</div>

												</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-xl-6">
									<section class="card card-featured-left card-featured-primary mb-3">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fas fa-life-ring"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Fitimi total</h4>
														<div class="info">
															<strong class="amount">{{$total}}$</strong>
														</div>
													</div>

												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-6">
									<section class="card card-featured-left card-featured-tertiary mb-3">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-tertiary">
														<i class="fas fa-shopping-cart"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Sot</h4>
														<div class="info">
															<strong class="amount">{{$today}}$</strong>
														</div>
													</div>

												</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-xl-6">
									<section class="card card-featured-left card-featured-quaternary">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-quaternary">
														<i class="fas fa-user"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Kete Vit</h4>
														<div class="info">
															<strong class="amount">{{$yearly}}$</strong>
														</div>
													</div>

												</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
</div>
@endsection
