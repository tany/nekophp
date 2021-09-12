// Routes

/                   core/Main
/login              core/Login
/logout             core/Login#logout

/example            core/Examples
/example/:id        core/Examples
/example/*path      core/Examples

# errors
@error/:code        core/Errors
