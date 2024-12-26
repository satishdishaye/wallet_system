<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-center">
          <h4>User Dashboard</h4>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rechargeModal">Recharge Wallet</button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payMerchantModal">Pay Merchant</button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewTransactionsModal">View Transactions</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recharge Wallet Modal -->
<div class="modal fade" id="rechargeModal" tabindex="-1" aria-labelledby="rechargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rechargeModalLabel">Recharge Wallet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="rechargeForm" method="post" action="{{route('recharge-wallet')}}">
            @csrf
          <div class="mb-3">
            <label for="rechargeAmount" class="form-label">Amount</label>
            <input type="number" class="form-control" name="amount" id="rechargeAmount" required>
            @error('amount')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary w-100">Proceed to Payment</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Pay Merchant Modal -->
<div class="modal fade" id="payMerchantModal" tabindex="-1" aria-labelledby="payMerchantModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payMerchantModalLabel">Pay Merchant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="payMerchantForm" method="post" action="{{route('transfer-amount')}}">
          @csrf
          <div class="mb-3">
            <label for="merchantPhone" class="form-label">Merchant Phone Number</label>
            <input type="text" class="form-control" name="number" id="merchantPhone" required>
          </div>
          <div class="mb-3">
            <label for="payAmount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" id="payAmount" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Pay Merchant</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- View Transactions Modal -->
<div class="modal fade" id="viewTransactionsModal" tabindex="-1" aria-labelledby="viewTransactionsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTransactionsModalLabel">Transaction History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Transaction history table (dynamically generated) -->
        <table class="table">
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody id="transactionHistoryBody">
            <!-- Transactions will be populated here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


<script>
  document.getElementById('viewTransactionsModal').addEventListener('show.bs.modal', function () {
      fetch("{{ route('view-transaction') }}")
          .then(response => response.json())
          .then(data => {
              let transactionHistoryBody = document.getElementById('transactionHistoryBody');
              transactionHistoryBody.innerHTML = ''; 
  
              let counter = 0; 
              data.forEach(transaction => {
                  counter += 1; 
                  let row = document.createElement('tr');
                  row.innerHTML = `
                      <td>${counter}</td>
                      <td>${transaction.amount}</td>
                      <td>${transaction.transaction_status}</td>
                      <td>${new Date(transaction.created_at).toLocaleString()}</td> <!-- Format date -->
                  `;
                  transactionHistoryBody.appendChild(row); 
              });
          })
          .catch(error => {
              console.error('Error fetching transactions:', error);
          });
  });
</script>

  

</body>
</html>
