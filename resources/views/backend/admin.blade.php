<H1> Admin Dashboard </H1>
<p> Welcome to the admin dashboard. </p>
<p> Here you can manage users, view reports, and perform administrative tasks. </p>
//logout button
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>
