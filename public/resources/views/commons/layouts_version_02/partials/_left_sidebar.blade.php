<!-- Vertical Nav -->
        <nav class="hk-nav hk-nav-dark">
            <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
            <div class="nicescroll-bar">
                <div class="navbar-nav-wrap">
                    <div class="nav-header">
                        <span>{{App\Language::trans('Main Navigation')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link" href="{{action('OpencartUsersController@getLogin')}}">
                                <span class="feather-icon"><i data-feather="log-in"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Login')}}</span>
                            </a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{action('DashboardsController@getDashboard')}}">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Dashboard')}}</span>
                            </a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{action('InternalTicketsController@getIndex')}}">
                                <span class="feather-icon"><i data-feather="mail"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Ticket')}}</span>
                            </a> 
                        </li>
                  
                    </ul>



                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('Setup')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#Components_drp">
                                <span class="feather-icon"><i data-feather="settings"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('General Setting')}}</span>
                            </a>
                            <ul id="Components_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('SettingsController@getIndex')}}">{{App\Language::trans('Company')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('CurrenciesController@getIndex')}}">{{App\Language::trans('Currencies')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('TaxesController@getIndex')}}">{{App\Language::trans('Taxes')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('PaymentTermsController@getIndex')}}">{{App\Language::trans('Payment Terms')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>   

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#locations_drp">
                                <span class="feather-icon"><i data-feather="type"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Location')}}</span>
                            </a>
                            <ul id="locations_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('CitiesController@getIndex')}}">{{App\Language::trans('Cities')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('StatesController@getIndex')}}">{{App\Language::trans('States')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('CountriesController@getIndex')}}">{{App\Language::trans('Countries')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>  

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#users_drp">
                                <span class="feather-icon"><i data-feather="user"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Users')}}</span>
                            </a>
                            <ul id="users_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UsersController@getIndex')}}">{{App\Language::trans('Users')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UserGroupsController@getIndex')}}">{{App\Language::trans('User Groups')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>            
                    </ul>

                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('E-Commerce')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#oc_product_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Products')}}</span>
                            </a>
                            <ul id="oc_product_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('OCProductsController@getIndex', 'index')}}">{{App\Language::trans('Main Warehouse')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('OCProductsController@getIndex', 'is_prepare_to_store')}}">{{App\Language::trans('Active Product')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('OCProductsController@getIndex', 'is_removed')}}">{{App\Language::trans('Removed Product')}}</a>
                                        </li>                                      
                                    </ul>
                                </li>
                            </ul>
                        </li>  
                     
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#oc_language_drp">
                                <span class="feather-icon"><i data-feather="type"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Language')}}</span>
                            </a>
                            <ul id="oc_language_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('OpencartLanguageTranslatorsController@getIndex')}}">{{App\Language::trans('Backend')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('OpencartLanguageTranslatorsController@getIndex')}}">{{App\Language::trans('Fontend')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('OpencartLanguageTranslatorsController@getTranslationStatus')}}">{{App\Language::trans('Log')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>     
                    </ul>


                   

                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('Accounting')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#acc_products_drp">
                                <span class="feather-icon"><i data-feather="calendar"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Booking')}}</span>
                            </a>
                            <ul id="acc_products_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('IframesController@getBookingFacility')}}">{{App\Language::trans('Facilitiy')}}</a>
                                        </li>                 
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#account_receivables_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Account Receivable')}}</span>
                            </a>
                            <ul id="account_receivables_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ARInvoicesController@getIndex')}}">{{App\Language::trans('AR Invoices')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ARPaymentReceivedsController@getIndex')}}">{{App\Language::trans('Payment Received')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ARRefundsController@getIndex')}}">{{App\Language::trans('Refund')}}</a>
                                        </li>                    
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    
                    
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#acc_reports_drp">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Report')}}</span>
                            </a>
                            <ul id="acc_reports_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ReportsController@getSalesReport')}}">{{App\Language::trans('Sales Report')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                                
                    </ul>



                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('Power Management')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#monitorings_drp">
                                <span class="feather-icon"><i data-feather="activity"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Monitoring')}}</span>
                            </a>
                            <ul id="monitorings_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UMeterRegistersController@getStatus')}}">{{App\Language::trans('Current Power')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UMeterRegistersController@getStatusDetail')}}">{{App\Language::trans('Current Power (Summary)')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UMeterReadingController@getIndex')}}">{{App\Language::trans('Meter Reading Status')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#usages_drp">
                                <span class="feather-icon"><i data-feather="zap"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Usages')}}</span>
                            </a>
                            <ul id="usages_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ReportsController@getRoomUsages')}}">{{App\Language::trans('Room Usages')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ReportsController@getMonthlyUsages')}}">{{App\Language::trans('Monthly Usages')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
            
                        <li class="nav-item">
                            <a class="nav-link" href="{{action('UMeterRegistersController@getIndex')}}">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Devices Setting')}}</span>
                            </a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#billings_drp">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Billing')}}</span>
                            </a>
                            <ul id="billings_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UMeterInvoiceController@getIndex')}}">{{App\Language::trans('Invoices')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ReportsController@getInvoices')}}">{{App\Language::trans('Invoices Reports')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('ReportsController@getMonthlySales')}}">{{App\Language::trans('Monthly Sales')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UMeterSubsidiariesController@getIndex')}}">{{App\Language::trans('Subsidy')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UtilityChargesController@getIndex')}}">{{App\Language::trans('Price Lists')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('UMeterClassController@getIndex')}}">{{App\Language::trans('Account')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#developers_drp">
                                <span class="feather-icon"><i data-feather="user-check"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Developers')}}</span>
                            </a>
                            <ul id="developers_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('DevelopersController@getPaymentTestIndex')}}">{{App\Language::trans('Payment Test')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>               
                    </ul>
  
                </div>
            </div>
        </nav>
        <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
        <!-- /Vertical Nav -->