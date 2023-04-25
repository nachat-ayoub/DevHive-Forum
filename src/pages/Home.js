import moment from "moment/moment";
import Navbar from "../compenents/Navbar";

function Home() {
    var questions = [
        {
            title: "Maximum recursion depth exceeded in databricks when converting pandas dataframe to spark dataframe",
            views: 1020,
            votes: 900,
            answers: 950,
            createdAt:new Date()

        },
        {
            title: "Minimum recursion depth exceeded in databricks when converting pandas dataframe to spark dataframe",
            views: 1000,
            votes: 890,
            answers: 750,
            createdAt: Date.now()


        }
    ]
    return (
        <div>
        <Navbar />
        <div className="container">
        <h1>Home</h1>
            <ul>
                {questions.map(function (question) {
                    return (
                        <li className="card mb-3">
                            <div className="card-body">
                            <h3 className="card-title">
                                <a href="#">

                                    {question.title}
                                </a>

                            </h3>
                            <div className="card-subtitle row">
                                
                                
                                <p className="col-10">views: {question.views} • votes: {question.votes} • answers: {question.answers}</p>
                            
                            <p className="col-2">
                                {
                                moment(question.createdAt).startOf('hour').fromNow()
                                
                                
                                }</p>

                            </div>
                            
                            </div>
                        
                       


                        </li>

                    )


                })}

            </ul>
            </div>
            
        </div>
        


    )
}
export default Home;