Vertical Nav -->
        <nav class="hk-nav hk-nav-dark">
            <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
            <div class="nicescroll-bar">
                <div class="navbar-nav-wrap">
                        
                    <div class="nav-header">
                        <span>{{App\Language::trans('Main Navigation')}}</span>
                        <span>UI</span>
                    </div>

                    <ul class="navbar-nav flex-column">

                    @if(!isset(Auth::User()->id))
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('OpencartUsersController@getLogin')}}">
                                <span class="feather-icon"><i data-feather="log-in"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Login')}}</span>
                            </a> 
                        </li>
                    @endif
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('DashboardsController@getUserProfile')}}">
                                <span class="feather-icon"><i data-feather="user"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Profile')}}</span>
                            </a> 
                        </li>
                   
                    @if(Auth::User()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('DashboardsController@getDashboard')}}">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Dashboard')}}</span>
                            </a> 
                        </li> 
                    @endif               
                  
                    @if(Auth::User()->is_super_admin)
                       <!--  <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('CadviewersController@getIndex')}}">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('CadViewer')}}</span>
                            </a> 
                        </li> 

                         <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('CadviewersController@getIndex2')}}">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('CadViewer Temp')}}</span>
                            </a> 
                        </li>  -->
                    @endif               
                  

                    </ul>


            @if(Auth::User()->is_admin)
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('Setup')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('InternalTicketsController@getIndex')}}">
                                <span class="feather-icon"><i data-feather="mail"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Ticket')}}</span>
                            </a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#Components_drp">
                                <span class="feather-icon"><i data-feather="settings"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('General Setting')}}</span>
                            </a>
                            <ul id="Components_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                             <!-- loading-label -->
                                            <a class="nav-link" href="{{action('SettingsController@getIndex')}}">{{App\Language::trans('Company')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('CurrenciesController@getIndex')}}">{{App\Language::trans('Currencies')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('TaxesController@getIndex')}}">{{App\Language::trans('Taxes')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('PaymentTermsController@getIndex')}}">{{App\Language::trans('Payment Terms')}}</a>
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
                                            <a class="nav-link loading-label" href="{{action('CitiesController@getIndex')}}">{{App\Language::trans('Cities')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('StatesController@getIndex')}}">{{App\Language::trans('States')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('CountriesController@getIndex')}}">{{App\Language::trans('Countries')}}</a>
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
                                            <a class="nav-link loading-label" href="{{action('UsersController@getIndex')}}">{{App\Language::trans('Users')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UserGroupsController@getIndex')}}">{{App\Language::trans('User Groups')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>            
                    </ul>

                @if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_E_COMMERCE))
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('Income And Expenses')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#income_expenses_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Expenses')}}</span>
                            </a>
                            <ul id="income_expenses_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('IETransactionsController@getIndex', 'index')}}">{{App\Language::trans('Transaction')}}</a>
                                        </li>                                   
                                    </ul>
                                </li>
                            </ul>
                        </li>  
                    </ul>
                @endif

                @if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_E_COMMERCE))
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('E-Commerce')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">

                         <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#skynet_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Skynet')}}</span>
                            </a>
                            <ul id="skynet_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('SkynetsController@getIndex')}}">{{App\Language::trans('Index')}}</a>
                                        </li>                                          
                                    </ul>
                                </li>
                            </ul>
                        </li> 

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#oc_product_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Products')}}</span>
                            </a>
                            <ul id="oc_product_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OCProductsController@getIndex', 'index')}}">{{App\Language::trans('Main Warehouse')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OCProductsController@getIndex', 'is_verified')}}">{{App\Language::trans('Active Product')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OCProductsController@getIndex', 'is_removed')}}">{{App\Language::trans('Removed Product')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OCProductsController@getNew')}}">{{App\Language::trans('Mobile Page')}}</a>
                                        </li>                                       
                                    </ul>
                                </li>
                            </ul>
                        </li>  
                     
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#oc_language_drp">
                                <span class="feather-icon"><i data-feather="type"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Go Leaf Language')}}</span>
                            </a>
                            <ul id="oc_language_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OpencartLanguageTranslatorsController@getEditFull')}}">{{App\Language::trans('Font-end')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OpencartLanguageTranslatorsController@getEditByWord')}}">{{App\Language::trans('Font-end (By Word)')}}</a>
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
                                            <a class="nav-link loading-label" href="{{action('OpencartLanguageTranslatorsController@getIndex')}}">{{App\Language::trans('Backend')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OpencartLanguageTranslatorsController@getIndex')}}">{{App\Language::trans('Fontend')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('OpencartLanguageTranslatorsController@getTranslationStatus')}}">{{App\Language::trans('Log')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>     
                    </ul>
                @endif

                   
                @if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING))
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span>{{App\Language::trans('Club House')}}</span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                            
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('TicketsController@getIndex')}}">
                                <span class="feather-icon"><i data-feather="log-in"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Ticket Complaint')}}</span>
                            </a> 
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#product_setting_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Product Setting')}}</span>
                            </a>
                            <ul id="product_setting_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                         <li class="nav-item">
                                             <a class="nav-link loading-label" href="{{action('ProductsController@getIndex')}}">{{App\Language::trans('Product')}}</a> 
                                         </li> 
                                         <li class="nav-item">
                                             <a class="nav-link loading-label" href="{{action('ProductCategoriesController@getIndex')}}">{{App\Language::trans('Product Category')}}</a> 
                                         </li>                                     
                                    </ul>
                                </li>
                            </ul>
                        </li> 

                        <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('MembershipsController@getIndex')}}">
                                <span class="feather-icon"><i data-feather="user"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Membership')}}</span>
                            </a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#acc_products_drp">
                                <span class="feather-icon"><i data-feather="calendar"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Booking')}}</span>
                            </a>
                            <ul id="acc_products_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('IframesController@getBookingFacility')}}">{{App\Language::trans('Facilitiy')}}</a>
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
                                            <a class="nav-link loading-label" href="{{action('ARInvoicesController@getIndex')}}">{{App\Language::trans('AR Invoices')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('ARPaymentReceivedsController@getIndex')}}">{{App\Language::trans('Payment Received')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('ARRefundsController@getIndex')}}">{{App\Language::trans('Refund')}}</a>
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
                                            <a class="nav-link loading-label" href="{{action('ReportsController@getSalesReport')}}">{{App\Language::trans('Sales Report')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                                
                    </ul>
                @endif

                @if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_POWER_MANAGEMENT))
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
                                            <a class="nav-link loading-label" href="{{action('UMeterRegistersController@getStatus')}}">{{App\Language::trans('Power Meter')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterRegistersController@getStatusDetail')}}">{{App\Language::trans('Power Meter Status(Summary)')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterReadingController@getIndex')}}">{{App\Language::trans('Meter Reading Status')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('VisitLogsController@getIndex')}}">{{App\Language::trans('Unit Maintenance Log')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#setting_drp">
                                <span class="feather-icon"><i data-feather="sliders"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Setting')}}</span>
                            </a>
                            <ul id="setting_drp" class="nav flex-column collapse collapse-level-1">
                           
                                <li class="nav-item">
                                    <ul class="nav flex-column">

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterClassController@getIndex')}}">{{App\Language::trans('Account')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UtilityChargesController@getIndex')}}">{{App\Language::trans('Price Lists')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('HelpsController@getIndex')}}">
                                               <!--  <span class="  "><i data-feather="user"></i></span> -->
                                                <span class="nav-link-text">{{App\Language::trans('FAQ Setup')}}</span>
                                            </a> 
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterRegistersController@getIndex')}}">
                                               <!--  <span class="feather-icon"><i data-feather="pie-chart"></i></span> -->
                                                <span class="nav-link-text">{{App\Language::trans('Devices Setting')}}</span>
                                            </a> 
                                        </li> 

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterSubsidiariesController@getIndex')}}">{{App\Language::trans('Complementary/Subsidy')}}</a>
                                        </li>

                                       

                               
                                    </ul>
                                </li>
                            </ul>
                        </li>
            
                        

                       
                         

<!-- 
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#billings_drp">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Billing')}}</span>
                            </a>
                            <ul id="billings_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterInvoiceController@getIndex')}}">{{App\Language::trans('Invoices')}}</a>
                                        </li>
        
                                    </ul>
                                </li>
                            </ul>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#usages_drp">
                                <span class="feather-icon"><i data-feather="archive"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Report')}}</span>
                            </a>
                            <ul id="usages_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                             <!-- loading-label -->
                                            <a class="nav-link" href="{{action('ReportsController@getRoomUsages')}}">{{App\Language::trans('Room Usages')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('ReportsController@getMonthlyUsages')}}">{{App\Language::trans('Monthly Usages')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('ReportsController@getMonthlySales')}}">{{App\Language::trans('Monthly Sales')}}</a>
                                        </li>

                                      <!--   <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('ReportsController@getInvoices')}}">{{App\Language::trans('Invoices Reports')}}</a>
                                        </li> -->
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link loading-label" href="{{action('UMeterRefundsController@getIndex')}}">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Refund')}}</span>
                            </a> 
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#developers_drp">
                                <span class="feather-icon"><i data-feather="user-check"></i></span>
                                <span class="nav-link-text">{{App\Language::trans('Developers')}}</span>
                            </a>
                            <ul id="developers_drp" class="nav flex-column collapse collapse-level-1">

                                 <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterPaymentReceivedDebugsController@getMobileAppTesting')}}">{{App\Language::trans('Mobile Apps Testing')}}</a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('DevelopersController@getPaymentTestIndex')}}">{{App\Language::trans('Payment Allow List')}}</a>
                                        </li>
                                    </ul>
                                </li>
                           
                               <!--  <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="{{action('UMeterPaymentReceivedDebugsController@getDebugIndex')}}">{{App\Language::trans('Payment Check')}}</a>
                                        </li>
                                    </ul>
                                </li>
 -->
                               
                            </ul>
                        </li>               
                    </ul>
                @endif
            @endif
                </div>
            </div>
        </nav>
        <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
        <!-- /Vertical Nav