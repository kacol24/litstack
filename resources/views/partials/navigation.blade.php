@auth('lit')
    <nav class="lit-navigation">

        @include('lit::partials.nav_loader')

        <lit-main-navigation :items="{{collect(lit()->config('navigation')->main)}}"></lit-navigation>
    </nav>
@endauth
