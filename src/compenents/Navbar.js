function Navbar() {
  return (
    <nav class="navbar bg-warning ">
      <div class="container-fluid">
        <a class="navbar-brand">DevHive</a>
        <form class="d-flex" role="search">
          <input
            class="form-control me-2"
            type="search"
            placeholder="Search"
            aria-label="Search"
          />
          <button class="btn btn-dark" type="submit">
            Search
          </button>
        </form>
      </div>
    </nav>
  );
}
export default Navbar;
