<H1> User Dashboard </H1>
<p> Welcome to the user dashboard. </p>
<p> Here you can manage your profile, view your reports, and perform user tasks. </p>
//logout button
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>
