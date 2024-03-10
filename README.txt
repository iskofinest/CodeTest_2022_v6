As I reading lines of codes for less than 2 hours, BookingRepository.php is a controller class for CRUD operation in Jobs per User. I assume that this is a piece of code from a running app with an old php version I think like version 5.
I admire the code because it is built to do a complex task such as language translation and email notification to user. The code looks good but there are some aspects missed to implement in the application.
Aspect Separation – BookingRepository class is too crowded because it also contains some aspect that can be extracted to other class that can also used by other class.
Request Layer: Since validation happens on the repository, the API already accepts the request before it was actually validated. Validating request before proceeding to the core logic can mitigate the server from unnecessary processing that can lead to server breakdown.
Redundancy: I saw some code repetition on the booking repository that contributes to the reduction of readability.
Code Formatting: longer code can be located into a new line for the sake of readability and maintainability

 because core business logic was placed in a repository class to separate it on the controller which is a good practice in creating every app. There is also some code redundancy in the BookingRepository that contributes to the longer line of codes.
However, on the repository class, there were a bunch of codes that is too crowded makes it not readable and I also think very hard to maintain. On the concept of separation of concern, many aspect can extract to the repository such as Data Accessing, Logging, validation, etc. so that only the core business logic concern will be put on the repository.
I also noticed that some of the Laravel framework features was not used such as Form Request, Route Model Binding, traits and many more that if properly utilized, there will be an ease of code reading and app maintenance.

On BookingRepository::store function, I used Form request to separate the request validation part to the business logic. In this way, the validation part will happen on request layer rather than the API accepts the request before it validates. Laravel Framework also has built-in features when using Form Request since it automatically throws error messages relevant to the request validation.
On BookingRepository::bookingExpireNoAccepted, I maximized using eloquent model for querying the data since it can be a concise approach and aligning with the established relationship on the Eloquent Model as ORM.
I also wanted to separate the logging and Data Accessing part to reduce lines of code in the repository.
I also used updated PHP version syntax because it makes the code simplier and maintainable.

I didn’t manage to refactor all the code because of lack of time.
There are a lot of improvements that can be done into the code to make it a better app.
