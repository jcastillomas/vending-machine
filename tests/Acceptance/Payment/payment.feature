Feature: Health check
  In order to check the availability of the service
  As unauthenticated User
  I need to be able to check the service status

  Scenario: It receives a valid request to insert a coin
    When I send a "POST" request to "/insert-coin" with body
      """
      "0.10"
      """
    Then the response code should be 202
    And the response body should be:
      """
      []
      """

    @truncateDatabaseTables
    Scenario: It receives a valid request to get and reset fund
    Given I have currencies
    Given I have fund
    When I send a "GET" request to "/return-coins"
    Then the response code should be 202
    And the response body should be:
      """
      [
          "1",
          "1",
          "0.25",
          "0.25",
          "0.1",
          "0.1",
          "0.05",
          "0.05"
      ]
      """
