Feature: app
    In absence of a BFF or Orchestrator to reach the goal
    we will use the current service to generate all the request to each context

  Scenario: It receives a valid request to change stock and product
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

  Scenario: It receives a valid request to change multiple stock and product
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

  Scenario: It receives a valid request to buy an item
    When I send a "GET" request to "/get-item" with body
      """
      "Water"
      """
    Then the response code should be 200
    And the response body should be:
      """
      [
        "WATER",
        "0.25",
        "0.10"
      ]
      """
