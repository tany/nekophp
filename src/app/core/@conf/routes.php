// Routes

/                   core/Main
/login              core/Login
/logout             core/Login#logout

/test               core/Tests
/test/:id           core/Tests
/test/*path         core/Tests

# errors
@error/:code        core/Errors
