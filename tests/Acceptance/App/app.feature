Feature: app
    In absence of a BFF or Orchestrator to reach the goal
    we will use the current service to generate all the request to each context

  Scenario: It receives a valid request to insert a coin
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
