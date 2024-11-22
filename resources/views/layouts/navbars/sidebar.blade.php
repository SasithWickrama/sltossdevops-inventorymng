<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal">{{ _('Process Management') }}</a>
        </div>
        <ul class="nav">
            <!-- <li @if ($pageSlug=='mainInventory' ) class="active " @endif>
                <a href="{{ route('maininventory') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ _('Dashboard') }}</p>
                </a>
            </li> -->
             <li>

                <div @if ($pageSlug=='createMajorProject' || $pageSlug=='updateMajorProject' || $pageSlug=='majorProjectInbox'  ) class="collapse show" @else class="collapse" @endif id="nav_project">
                    <ul class="nav pl-4">
                    <li @if ($pageSlug=='majorProjectInbox' ) class="active " @endif>
                            <a href="{{ route('majorProjectInbox')  }}">
                                <i class="tim-icons icon-wallet-43"></i>
                                <p>{{ _('Inbox') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug=='createMajorProject' ) class="active " @endif>
                            <a href="{{ route('createMajorProjectView')  }}">
                                <i class="tim-icons icon-map-big"></i>
                                <p>{{ _('Create Project') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug=='updateMajorProject' ) class="active " @endif>
                            <a href="{{ route('updateMajorProjectView')  }}">
                                <i class="tim-icons icon-book-bookmark"></i>
                                <p>{{ _('Update Project') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#nav_job" aria-expanded="true">
                    <i class="tim-icons icon-paper"></i>
                    <span class="nav-link-text">{{ __('Jobs') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div @if ($pageSlug=='createProject' || $pageSlug=='updateProject' || $pageSlug=='jobInbox'  ) class="collapse show" @else class="collapse" @endif id="nav_job">
                    <ul class="nav pl-4">
                    <li @if ($pageSlug=='jobInbox' ) class="active " @endif>
                            <a href="{{ route('jobInbox')  }}">
                                <i class="tim-icons icon-wallet-43"></i>
                                <p>{{ _('Inbox') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug=='createProject' ) class="active " @endif>
                            <a href="{{ route('createProjectView')  }}">
                                <i class="tim-icons icon-map-big"></i>
                                <p>{{ _('Create Job') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug=='updateProject' ) class="active " @endif>
                            <a href="{{ route('updateProjectView')  }}">
                                <i class="tim-icons icon-book-bookmark"></i>
                                <p>{{ _('Update Job') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> 
            <li>
                <a data-toggle="collapse" href="#Inventory" aria-expanded="true">
                    <i class="tim-icons icon-app"></i>
                    <span class="nav-link-text">{{ __('Inventory') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div @if ($pageSlug=='inventoryMainProject' || $pageSlug=='inventoryProject' || $pageSlug=='inventoryProjectInbox' ||$pageSlug=='mainInventory' ) class="collapse show" @else class="collapse" @endif id="Inventory">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug=='mainInventory' ) class="active " @endif>
                            <a href="{{ route('maininventory')  }}">
                                <i class="tim-icons icon-single-copy-04"></i>
                                <p>{{ _('Main Inventory') }}</p>
                            </a>
                        </li>
                         <li @if ($pageSlug=='inventoryMainProject' ) class="active " @endif>
                            <a href="{{ route('inventory/project')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ _('Project Inventory') }}</p>
                            </a>
                        </li> 
                         <li>
                            <a data-toggle="collapse" href="#inventoryProject" aria-expanded="true">
                                <i class="tim-icons icon-components"></i>
                                <span class="nav-link-text">{{ __('Jobs') }}</span>
                                <b class="caret mt-1"></b>
                            </a>

                            <div  @if ($pageSlug =='inventoryProject' || $pageSlug=='inventoryProjectInbox'  ) class="collapse show" @else class="collapse" @endif id="inventoryProject">
                                <ul class="nav pl-4">
                                    <li @if ($pageSlug=='inventoryProjectInbox' ) class="active " @endif>
                                        <a href="{{ route('projectInbox')  }}">
                                            <i class="tim-icons icon-single-copy-04"></i>
                                            <p>{{ _('Inbox') }}</p>
                                        </a>
                                    </li>
                                    <li @if ($pageSlug=='inventoryProject' ) class="active " @endif>
                                        <a href="{{ route('project')  }}">
                                            <i class="tim-icons icon-notes"></i>
                                            <p>{{ _('Job Data') }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li> 
                    </ul>
                </div>
            </li>



        </ul>
    </div>
</div>