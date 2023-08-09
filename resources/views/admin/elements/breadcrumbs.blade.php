    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                {{-- <h3 class="kt-subheader__title"> All Pages List </h3> --}}
                 {{-- bread crumb --}}
                <span class="kt-subheader__separator kt-hidden"></span>
                <?php $segments = request()->segments(); 
                ?>
                @if(count($segments) > 1)
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.dashboard') }}" class="kt-subheader__breadcrumbs-link">Dashboard</a>
                    <!-- <span class="kt-subheader__breadcrumbs-separator"></span> -->
                    <?php $url = 'admin'; ?>
                    @for($i=1; $i < count($segments); $i++)
                        <?php 
                            if(($i + 2) == count($segments) && is_numeric($segments[$i + 1])) {
                                $url .= '/'. $segments[$i] . '/' . $segments[$i + 1];
                            } else {
                                $url .= '/'. $segments[$i];
                            }
                        ?>
                        <?php if (!is_numeric($segments[$i]) && ($segments[$i] != "dashboard")): ?>
                            <span class="kt-subheader__breadcrumbs-separator"></span>
                            <a href="{{ $url }}" class="kt-subheader__breadcrumbs-link">{{ breadCrumbTitle($segments[$i]) }}</a>
                        <?php endif ?>
                    @endfor
                </div>
                @endif
            </div>
        </div>
    </div>