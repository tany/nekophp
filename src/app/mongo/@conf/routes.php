// Routes

/mongo                        mongo/Main
/mongo/:client/:db            mongo/Databases#REST
/mongo/:client/:db/:coll      mongo/Collections#REST
/mongo/:client/:db/:coll/:id  mongo/Documents#REST
