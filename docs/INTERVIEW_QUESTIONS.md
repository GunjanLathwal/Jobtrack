# JobTrack interviewer questions

## PHP / architecture

1. Why did you choose Core PHP instead of Laravel?
2. What is the PHP request lifecycle in this project?
3. Why use a front controller?
4. Why separate controllers, models and views?
5. What does `declare(strict_types=1)` do?
6. What does constructor property promotion do?
7. Where did you use encapsulation?
8. Why did you not force inheritance/interfaces everywhere?

## Authentication

9. How does registration work?
10. Why use `password_hash()`?
11. How does `password_verify()` work conceptually?
12. Why regenerate the session ID after login?
13. What is session fixation?
14. How do protected routes work?

## PDO / MySQL

15. Why PDO?
16. What are prepared statements?
17. How do they reduce SQL injection risk?
18. Why are foreign keys important?
19. Which indexes did you add and why?
20. Why use InnoDB?
21. Why use `ON DELETE CASCADE` for application events?

## Security

22. How do you prevent XSS?
23. Why is client-side validation insufficient?
24. What is CSRF?
25. Where do CSRF tokens appear in this project?
26. Why should database credentials not be committed?
27. What happens to SQL errors in production?

## API

28. What makes these endpoints REST-style?
29. Difference between 401 and 403?
30. Difference between 400 and 422?
31. Why return 201 after POST?
32. How is the authenticated user associated with API data?

## SQL / analytics

33. How is response rate calculated?
34. Why use SQL aggregation instead of fetching every row into PHP?
35. How would you paginate applications?
36. Which queries need indexes as the dataset grows?

## Scenario questions

37. What prevents user A from reading user B's application?
38. What happens if an application is deleted?
39. How would you add email reminders?
40. How would you support file uploads securely?
41. How would you test this application?
42. How would you deploy it using Nginx and PHP-FPM?
