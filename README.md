# Vending Machine

Example of a vending machine.

## Getting started

See all the documentation about to start with this project in [GettingStarted](resources/docs/GettingStarted.md).

## Goal

See all the documentation of the [Goal](resources/docs/Goal.md) of this project.

## Design considerations

In order to accomplish all the goal we need to take some considerations,
these considerations are related about the implementation that we need to do,
and how it will influence in the architecture of the project.

Before read the next documentation, please take a look on the [Goal](resources/docs/Goal.md) to understand the point of this project.

After knowing the point, let's start to talk about that this project is an approach to solve the goal,
there's no unique solution to solve it, and all can be valid, in this case we're going to start identifying
the main flows to extract the use cases and figures that involve this project.

Main flows:

- I'm able to insert money into the VM (Vending machine)
- I'm able to return back the money that I introduced
- I'm able to GET-ITEM in order to buy an item if I have funds enough in the VM
  - If I order an item and I add more funds that I need, the VM must return the exceed amount of funds
    - We must return the greatest coin first (Example 3)
- I'm able to SET the amount of stock and the available change in the VM with a service tool

Right now we are not going take new flows, but it will change in the future,
for example in the future probably we will want a button to know the price of a product.

With the before flows in the mind, we can start to identify the Models that we will have:

- Products: Items of the machine with price.
- Currency: Value of a coin and the currency of it.
- Stock: list of items with the amount of them in the VM.
- Cash: list of currencies and the amount of them in the VM.
- Funds: list of currencies amd the mount of them in a current transaction of the VM.

With the figures in mind, we can start to split in 3 main contexts:

- Devices
   - We will include an aggregate with VendingMachines.

- Payment:
  - We will include an aggregate with Currencies (Value and Currency).
  - We will include an aggregate with Cash (VM, Currency and amount).
  - We will include an aggregate with Fund (VM, Currency and amount).

- Product:
  - We will include an aggregate with Products (ProductName and Value).
  - We will include an aggregate with Stock (VM, Product and amount).

We consider store the stock inside Product and the cash inside Payment context in order to reduce the amount of times that the context will interact with them.
But it is a design consideration, we can create a new context for each of them or even put inside the Device context.

## Tech challenge

<details>
<summary>1. Single request Interacts with different services</summary>
We have 2 flows that breaks the conditions above of interacting just with a single context, these are, the GET-ITEM
flow that it needs to interact at the same time with the payment and the product context,
the same happens with the flow of service tool that manage the amount values of the VM.

Both cases are requesting with a single request things of both context

In this case we will need changes that enable us to request it. This thing needs to take care about that the all transaction in the request is done or roll-back everything on error.
We can do it via BFF or orchestrator for example. In our case in order to be faster we will include this logic inside our project in a folder named APP
this thing will simulate the orchestration between our contexts, this is not a well design solution, it's better that this logic is handled outside the back-end.

But doing this trick that we said before, we can enable a route that can handle a request and interact with different context all with keeping the transactionality.
A well-design solution can be to move this logic inside a BFF, allowing the client to request to the BFF this kind of requests, and the BFF will split the request in more request for each service
that needs to interact.
</details>


<details>
<summary>2. The Goal just works for an existing machine</summary>
In our system we designed an escalating solution to be able to have more than 1 vending machine.

The goal is the functionality of the vending-machine not the escalation solution, so we are going prepare the system for the future
just in case that we need more than 1 vending machine, but right now, we only need for the goal one simple vending machine.

In this case we will create a vending machine on project starts if not exists, and then we will use this vending machine for all requests.
</details>

## API documentation with all models and end-points

You can find an openapi doc [here](../openapi/api-doc.yaml).
