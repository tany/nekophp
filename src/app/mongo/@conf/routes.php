// Routes

/mongo                     mongo/Main
/mongo/db/:db              mongo/Databases#REST
/mongo/db/:db/:coll        mongo/Collections#REST
/mongo/db/:db/:coll/:id    mongo/Documents#REST
