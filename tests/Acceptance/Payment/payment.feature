Feature: Health check
  In order to check the availability of the service
  As unauthenticated User
  I need to be able to check the service status

  Scenario: It receives a valid request to health check endpoint
    When I send a "POST" request to "/insert-coin" with body
      """
      "0.10"
      """
    Then the response code should be 202
    And the response body should be:
      """
      []
      """
