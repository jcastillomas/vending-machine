Feature: app
    In absence of a BFF or Orchestrator to reach the goal
    we will use the current service to generate all the request to each context

  @truncateDatabaseTables
  Scenario: It receives a valid request to change stock and product
      Given I have currencies
      Given I have fund
      Given I have cash
    When I send a "PUT" request to "/service" with body
      """
      {
        "stock": [
          {
            "product_name": "Water",
            "stock": 0
          }
        ],
        "cash": [
          {
            "currency_value": "0.10",
            "amount": 0
          }
        ]
      }
      """
    Then the response code should be 202
    And the response body should be:
      """
      []
      """
  @truncateDatabaseTables
  Scenario: It receives a valid request to change multiple stock and product
    Given I have currencies
    Given I have fund
    Given I have cash
    When I send a "PUT" request to "/service" with body
      """
      {
        "stock": [
          {
            "product_name": "Water",
            "stock": 12
          },
          {
            "product_name": "Soda",
            "stock": 24
          }
        ],
        "cash": [
          {
            "currency_value": "0.10",
            "amount": 15
          },
          {
            "currency_value": "0.25",
            "amount": 30
          }
        ]
      }
      """
    Then the response code should be 202
    And the response body should be:
      """
      []
      """

  @truncateDatabaseTables
  Scenario: It receives a valid request to buy an item
    Given I have currencies
    Given I have fund
    Given I have cash
    When I send a "POST" request to "/get-item" with body
      """
      "Water"
      """
    Then the response code should be 200
    And the response body should be:
      """
      [
        "WATER",
        1,
        1,
        0.1,
        0.05
      ]
      """
