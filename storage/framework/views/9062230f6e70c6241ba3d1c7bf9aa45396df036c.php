Vertical Nav -->
        <nav class="hk-nav hk-nav-dark">
            <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
            <div class="nicescroll-bar">

                <div class="row">
                       
                        <div class="col-md-11 mt-15" style="margin-left:2%;">
                            <?php  $selected_group_id = Cookie::get('group_id'); ?>
                            <?php echo Form::select('leaf_group_id', App\UserAssign::groupCombobox(), $selected_group_id, ['class'=>'form-control select2','onchange'=>'init_group_change(this)']); ?>

                            <?php echo $errors->first('leaf_group_id', '<label for="leaf_group_id" class="help-block error">:message</label>'); ?>

                        </div>
                        <div class="col-md-1"></div>
   
                    </div>
                <div class="navbar-nav-wrap">
                        
                    <div class="nav-header">
                        <span><?php echo e(App\Language::trans('Main Navigation')); ?></span>
                        <span>UI</span>
                    </div>
            
                    <ul class="navbar-nav flex-column">

                    <?php if(!isset(Auth::User()->id)): ?>
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('OpencartUsersController@getLogin')); ?>">
                                <span class="feather-icon"><i data-feather="log-in"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Login')); ?></span>
                            </a> 
                        </li>
                    <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('DashboardsController@getUserProfile')); ?>">
                                <span class="feather-icon"><i data-feather="user"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Profile')); ?></span>
                            </a> 
                        </li>
                   
                    <?php if(Auth::User()->is_admin): ?>
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('DashboardsController@getDashboard')); ?>">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Dashboard')); ?></span>
                            </a> 
                        </li> 
                    <?php endif; ?>               
                  
                    <?php if(Auth::User()->is_super_admin): ?>
                       <!--  <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('CadviewersController@getIndex')); ?>">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('CadViewer')); ?></span>
                            </a> 
                        </li> 

                         <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('CadviewersController@getIndex2')); ?>">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('CadViewer Temp')); ?></span>
                            </a> 
                        </li>  -->
                    <?php endif; ?>               
                  

                    </ul>


            <?php if(Auth::User()->is_admin): ?>
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span><?php echo e(App\Language::trans('Setup')); ?></span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('InternalTicketsController@getIndex')); ?>">
                                <span class="feather-icon"><i data-feather="mail"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Ticket')); ?></span>
                            </a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#Components_drp">
                                <span class="feather-icon"><i data-feather="settings"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('General Setting')); ?></span>
                            </a>
                            <ul id="Components_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                             <!-- loading-label -->
                                            <a class="nav-link" href="<?php echo e(action('SettingsController@getIndex')); ?>"><?php echo e(App\Language::trans('Company')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('CurrenciesController@getIndex')); ?>"><?php echo e(App\Language::trans('Currencies')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('TaxesController@getIndex')); ?>"><?php echo e(App\Language::trans('Taxes')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('PaymentTermsController@getIndex')); ?>"><?php echo e(App\Language::trans('Payment Terms')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>   

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#locations_drp">
                                <span class="feather-icon"><i data-feather="type"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Location')); ?></span>
                            </a>
                            <ul id="locations_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('CitiesController@getIndex')); ?>"><?php echo e(App\Language::trans('Cities')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('StatesController@getIndex')); ?>"><?php echo e(App\Language::trans('States')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('CountriesController@getIndex')); ?>"><?php echo e(App\Language::trans('Countries')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>  

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#users_drp">
                                <span class="feather-icon"><i data-feather="user"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Users')); ?></span>
                            </a>
                            <ul id="users_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UsersController@getIndex')); ?>"><?php echo e(App\Language::trans('Users')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UserGroupsController@getIndex')); ?>"><?php echo e(App\Language::trans('User Groups')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>            
                    </ul>

                <?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_E_COMMERCE)): ?>
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span><?php echo e(App\Language::trans('Income And Expenses')); ?></span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#income_expenses_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Expenses')); ?></span>
                            </a>
                            <ul id="income_expenses_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('IETransactionsController@getIndex', 'index')); ?>"><?php echo e(App\Language::trans('Transaction')); ?></a>
                                        </li>                                   
                                    </ul>
                                </li>
                            </ul>
                        </li>  
                    </ul>
                <?php endif; ?>

                <?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_E_COMMERCE)): ?>
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span><?php echo e(App\Language::trans('E-Commerce')); ?></span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">

                         <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#skynet_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Skynet')); ?></span>
                            </a>
                            <ul id="skynet_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('SkynetsController@getIndex')); ?>"><?php echo e(App\Language::trans('Index')); ?></a>
                                        </li>                                          
                                    </ul>
                                </li>
                            </ul>
                        </li> 

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#oc_product_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Products')); ?></span>
                            </a>
                            <ul id="oc_product_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OCProductsController@getIndex', 'index')); ?>"><?php echo e(App\Language::trans('Main Warehouse')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OCProductsController@getIndex', 'is_verified')); ?>"><?php echo e(App\Language::trans('Active Product')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OCProductsController@getIndex', 'is_removed')); ?>"><?php echo e(App\Language::trans('Removed Product')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OCProductsController@getNew')); ?>"><?php echo e(App\Language::trans('Mobile Page')); ?></a>
                                        </li>                                       
                                    </ul>
                                </li>
                            </ul>
                        </li>  
                     
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#oc_language_drp">
                                <span class="feather-icon"><i data-feather="type"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Go Leaf Language')); ?></span>
                            </a>
                            <ul id="oc_language_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OpencartLanguageTranslatorsController@getEditFull')); ?>"><?php echo e(App\Language::trans('Font-end')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OpencartLanguageTranslatorsController@getEditByWord')); ?>"><?php echo e(App\Language::trans('Font-end (By Word)')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>     

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#oc_language_drp">
                                <span class="feather-icon"><i data-feather="type"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Language')); ?></span>
                            </a>
                            <ul id="oc_language_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OpencartLanguageTranslatorsController@getIndex')); ?>"><?php echo e(App\Language::trans('Backend')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OpencartLanguageTranslatorsController@getIndex')); ?>"><?php echo e(App\Language::trans('Fontend')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('OpencartLanguageTranslatorsController@getTranslationStatus')); ?>"><?php echo e(App\Language::trans('Log')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>     
                    </ul>
                <?php endif; ?>

                   
                <?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING)): ?>
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span><?php echo e(App\Language::trans('Club House')); ?></span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">
                            
                        <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('TicketsController@getIndex')); ?>">
                                <span class="feather-icon"><i data-feather="log-in"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Ticket Complaint')); ?></span>
                            </a> 
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#product_setting_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Product Setting')); ?></span>
                            </a>
                            <ul id="product_setting_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                         <li class="nav-item">
                                             <a class="nav-link loading-label" href="<?php echo e(action('ProductsController@getIndex')); ?>"><?php echo e(App\Language::trans('Product')); ?></a> 
                                         </li> 
                                         <li class="nav-item">
                                             <a class="nav-link loading-label" href="<?php echo e(action('ProductCategoriesController@getIndex')); ?>"><?php echo e(App\Language::trans('Product Category')); ?></a> 
                                         </li>                                     
                                    </ul>
                                </li>
                            </ul>
                        </li> 

                        <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('MembershipsController@getIndex')); ?>">
                                <span class="feather-icon"><i data-feather="user"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Membership')); ?></span>
                            </a> 
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#acc_products_drp">
                                <span class="feather-icon"><i data-feather="calendar"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Booking')); ?></span>
                            </a>
                            <ul id="acc_products_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('IframesController@getBookingFacility')); ?>"><?php echo e(App\Language::trans('Facilitiy')); ?></a>
                                        </li>                 
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#account_receivables_drp">
                                <span class="feather-icon"><i data-feather="layout"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Account Receivable')); ?></span>
                            </a>
                            <ul id="account_receivables_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('ARInvoicesController@getIndex')); ?>"><?php echo e(App\Language::trans('AR Invoices')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('ARPaymentReceivedsController@getIndex')); ?>"><?php echo e(App\Language::trans('Payment Received')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('ARRefundsController@getIndex')); ?>"><?php echo e(App\Language::trans('Refund')); ?></a>
                                        </li>                    
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    
                    
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#acc_reports_drp">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Report')); ?></span>
                            </a>
                            <ul id="acc_reports_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('ReportsController@getSalesReport')); ?>"><?php echo e(App\Language::trans('Sales Report')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                                
                    </ul>
                <?php endif; ?>

                <?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_POWER_MANAGEMENT)): ?>
                    <hr class="nav-separator">
                    <div class="nav-header">
                        <span><?php echo e(App\Language::trans('Power Management')); ?></span>
                        <span>UI</span>
                    </div>
                    <ul class="navbar-nav flex-column">


                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#monitorings_drp">
                                <span class="feather-icon"><i data-feather="activity"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Monitoring')); ?></span>
                            </a>
                            <ul id="monitorings_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">

                                        <li class="nav-item">
                                            <a class="nav-link" href="<?php echo e(action('MeterRelayTestsController@getIndex')); ?>"><?php echo e(App\Language::trans('Remote On/Off History')); ?></a>
                                        </li>
                                        
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterRegistersController@getStatus')); ?>"><?php echo e(App\Language::trans('Power Meter')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterRegistersController@getStatusDetail')); ?>"><?php echo e(App\Language::trans('Power Meter Status(Summary)')); ?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterReadingController@getIndex')); ?>"><?php echo e(App\Language::trans('Meter Reading Status')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('VisitLogsController@getIndex')); ?>"><?php echo e(App\Language::trans('Unit Maintenance Log')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#setting_drp">
                                <span class="feather-icon"><i data-feather="sliders"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Setting')); ?></span>
                            </a>
                            <ul id="setting_drp" class="nav flex-column collapse collapse-level-1">
                           
                                <li class="nav-item">
                                    <ul class="nav flex-column">

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterClassController@getIndex')); ?>"><?php echo e(App\Language::trans('Account')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UtilityChargesController@getIndex')); ?>"><?php echo e(App\Language::trans('Price Lists')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('HelpsController@getIndex')); ?>">
                                               <!--  <span class="  "><i data-feather="user"></i></span> -->
                                                <span class="nav-link-text"><?php echo e(App\Language::trans('FAQ Setup')); ?></span>
                                            </a> 
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterRegistersController@getIndex')); ?>">
                                               <!--  <span class="feather-icon"><i data-feather="pie-chart"></i></span> -->
                                                <span class="nav-link-text"><?php echo e(App\Language::trans('Devices Setting')); ?></span>
                                            </a> 
                                        </li> 

                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterSubsidiariesController@getIndex')); ?>"><?php echo e(App\Language::trans('Complementary/Subsidy')); ?></a>
                                        </li>

                                       

                               
                                    </ul>
                                </li>
                            </ul>
                        </li>
            
                        

                       
                         

<!-- 
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#billings_drp">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Billing')); ?></span>
                            </a>
                            <ul id="billings_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterInvoiceController@getIndex')); ?>"><?php echo e(App\Language::trans('Invoices')); ?></a>
                                        </li>
        
                                    </ul>
                                </li>
                            </ul>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#usages_drp">
                                <span class="feather-icon"><i data-feather="archive"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Report')); ?></span>
                            </a>
                            <ul id="usages_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                             <!-- loading-label -->
                                            <a class="nav-link" href="<?php echo e(action('ReportsController@getRoomUsages')); ?>"><?php echo e(App\Language::trans('Room Usages')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="<?php echo e(action('ReportsController@getMonthlyUsages')); ?>"><?php echo e(App\Language::trans('Monthly Usages')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="<?php echo e(action('ReportsController@getMonthlySales')); ?>"><?php echo e(App\Language::trans('Monthly Sales')); ?></a>
                                        </li>

                                     

                                      <!--   <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('ReportsController@getInvoices')); ?>"><?php echo e(App\Language::trans('Invoices Reports')); ?></a>
                                        </li> -->
                                    </ul>
                                </li>
                            </ul>
                        </li>

                         <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#util_drp">
                                <span class="feather-icon"><i data-feather="archive"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Utility Fee')); ?></span>
                            </a>
                            <ul id="util_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                             <!-- loading-label -->
                                            <a class="nav-link" href="<?php echo e(action('UtilHouseController@getIndex')); ?>"><?php echo e(App\Language::trans('House')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="<?php echo e(action('UtilRoomsController@getIndex')); ?>"><?php echo e(App\Language::trans('Room')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="<?php echo e(action('UtilityFeesController@getIndex')); ?>"><?php echo e(App\Language::trans('Utillity Fee')); ?></a>
                                        </li>

                                     

                                      <!--   <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('ReportsController@getInvoices')); ?>"><?php echo e(App\Language::trans('Invoices Reports')); ?></a>
                                        </li> -->
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterRefundsController@getIndex')); ?>">
                                <span class="feather-icon"><i data-feather="pie-chart"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Refund')); ?></span>
                            </a> 
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#developers_drp">
                                <span class="feather-icon"><i data-feather="user-check"></i></span>
                                <span class="nav-link-text"><?php echo e(App\Language::trans('Developers')); ?></span>
                            </a>
                            <ul id="developers_drp" class="nav flex-column collapse collapse-level-1">

                                 <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('UMeterPaymentReceivedDebugsController@getMobileAppTesting')); ?>"><?php echo e(App\Language::trans('Mobile Apps Testing')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link loading-label" href="<?php echo e(action('DevelopersController@getPaymentTestIndex')); ?>"><?php echo e(App\Language::trans('Payment Allow List')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                           

                               
                            </ul>
                        </li>               
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
                </div>
            </div>
        </nav>
        <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>

       
        <script type="text/javascript">
            var updateSelectedGroupxUrl = "<?php echo e(action('SettingsController@updateSelectedGroup')); ?>";
            function init_group_change(me)
            {
                alert($(me).val());
                 $.get(updateSelectedGroupxUrl, { leaf_group_id: $(me).val() }, function(data) {
                     
                    console.log(data);
                      if(data['status_code'] == true){
                         location.reload();
                      }else{
                        alert('False');
                      }

                   

                 }, "json");
            }
        </script>
        <!-- /Vertical Nav