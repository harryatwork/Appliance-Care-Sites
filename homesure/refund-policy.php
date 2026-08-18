<?php
$pageTitle = 'Refund Policy | Home Sure';
$pageDescription = 'Home Sure\'s refund, cancellation and warranty policy for appliance repair bookings.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Refund Policy</span>
  </nav>
</div>

<section class="page-hero">
  <div class="container">
    <h1 class="reveal in-view">Refund, Cancellation &amp; Warranty Policy</h1>
  </div>
</section>

<section class="pad-sm">
  <div class="container">
    <div class="legal-content glass reveal">
      <p class="legal-content__updated">Last updated: <?php echo date('F j, Y'); ?></p>

      <h2>1. Overview</h2>
      <p>This policy explains how Home Sure Home Appliance Repair &amp; Services handles booking cancellations, visiting/inspection charges, refunds, and warranty claims. It should be read together with our Terms &amp; Conditions. A "refund" (return of money already paid) and a "warranty claim" (a request to fix or make good a covered issue) are handled differently, as explained below.</p>

      <h2>2. Booking Cancellation</h2>
      <p>You may cancel a booking free of charge at least 30 minutes before the scheduled technician arrival time, subject to the specific circumstances of your booking.</p>

      <h2>3. Cancellation Before Technician Arrival</h2>
      <p>Cancellations made within the window described in Section 2 above will not attract any charge.</p>

      <h2>4. Cancellation After Technician Arrival or Dispatch</h2>
      <p>If you cancel after the technician has been dispatched, or after the technician has arrived at your location, Home Sure may charge the applicable visiting/cancellation charge, as this reflects time and resources already committed to your booking. This charge is not arbitrary or punitive — it reflects the visiting/inspection charge structure described in Section 5 below.</p>

      <h2>5. Visiting/Inspection Charges</h2>
      <p>A visiting/inspection charge, generally ranging from approximately ₹199 to approximately ₹449 or more depending on the appliance/service, may apply where:</p>
      <ul>
        <li>You decline the recommended repair/service after the technician's inspection;</li>
        <li>You do not proceed with the repair/service after the technician's visit;</li>
        <li>The repair cannot be completed due to circumstances attributable to the appliance or customer; or</li>
        <li>The applicable service terms specify such a charge.</li>
      </ul>
      <p>Where you proceed with the repair, this charge may be adjusted against your final bill. Where you do not proceed, the visiting/inspection charge is generally payable and is not automatically refundable.</p>

      <h2>6. Repair/Service Cancellation After Work Has Started</h2>
      <p>If you cancel a repair after work has already commenced, charges may apply for work completed and any parts already procured or opened for your repair, as communicated to you at the time.</p>

      <h2>7. Failed or Unavailable Repair</h2>
      <p>If Home Sure is unable to complete a repair (for example, because a required part is unavailable, the appliance is obsolete, or the repair is not technically feasible), you will only be charged the applicable visiting/inspection charge (if applicable) and any cost of parts already ordered specifically for your appliance; you will not be charged for repair work that was not completed.</p>

      <h2>8. Overpayments</h2>
      <p>If you have paid more than the amount actually due, please contact us at <?php echo SITE_EMAIL; ?> with your booking/invoice details. Verified overpayments will be refunded as per Section 15 (Refund Method) and Section 16 (Processing Time) below.</p>

      <h2>9. Duplicate Payments</h2>
      <p>If a payment is processed more than once for the same booking due to a technical or payment-gateway error, please contact us with proof of both transactions. Verified duplicate payments will be refunded in full.</p>

      <h2>10. Spare Parts</h2>
      <p>Spare parts that have been specifically ordered for your appliance or already installed are generally non-refundable once ordered/fitted, except where the part is found to be defective, incorrect, or unsuitable due to an error on Home Sure's part (see Section 11 below), or where applicable law otherwise entitles you to a remedy.</p>

      <h2>11. Defective or Incorrect Spare Parts</h2>
      <p>If a spare part supplied or installed by Home Sure is found to be defective, incorrect, or unsuitable, please contact us at <?php echo SITE_EMAIL; ?>. We will inspect the issue and determine the appropriate remedy, which may include repair, replacement, or another appropriate resolution, depending on the circumstances.</p>

      <h2>12. Warranty-Related Claims</h2>
      <p>If an issue arises within the applicable service or spare-part warranty period (see Sections 24 and 25 of our Terms &amp; Conditions, and the specific period noted on your invoice/job sheet), please contact us with your invoice/job sheet details. We will assess the claim and, where it is a valid warranty claim, resolve it through repair, replacement, or other appropriate remedy — this is different from, and does not automatically entitle you to, a cash refund. Warranty exclusions (Section 26 of our Terms &amp; Conditions) will be assessed genuinely and are not used to automatically deny a legitimate claim.</p>

      <h2>13. Refund Eligibility</h2>
      <p>You may be eligible for a refund in situations such as: verified overpayment, duplicate payment, a booking cancelled within the free-cancellation window where payment was already collected, or a service that could not be delivered due to a failure on Home Sure's part.</p>

      <h2>14. Non-Refundable Situations</h2>
      <p>Amounts are generally not refundable in situations such as: the applicable visiting/inspection charge where you decline or do not proceed with the recommended service after inspection; charges for work already completed; and spare parts already ordered specifically for you or installed, except as described in Sections 10–11 above.</p>

      <h2>15. Refund Method</h2>
      <p>Where a refund is due, it will be processed to the original payment method used, to the extent reasonably possible. Where this is not possible (for example, in the case of a cash payment), we will agree an alternative method with you.</p>

      <h2>16. Refund Processing Time</h2>
      <p>Refunds are generally processed within 5–7 business days of approval. Please note that the actual time for the refunded amount to reflect in your account may vary depending on your bank or payment provider's own processing timelines, which are beyond Home Sure's control.</p>

      <h2>17. Bank/Payment-Provider Delays</h2>
      <p>If a refund has not reflected in your account within a reasonable time after processing by Home Sure, please contact us with your transaction details so we can help you follow up, including, where necessary, with the relevant bank or payment provider.</p>

      <h2>18. Dispute/Claim Process</h2>
      <p>If you disagree with a charge, a declined warranty claim, or a refund decision, please write to <?php echo SITE_EMAIL; ?> with your booking/invoice details and a description of the issue. We will review your case and respond within a reasonable time. If the matter remains unresolved, you may pursue any remedy available to you under applicable Indian consumer-protection law.</p>

      <h2>19. Contact Information</h2>
      <p><strong>Home Sure Home Appliance Repair &amp; Services</strong><br>
      Email: <?php echo SITE_EMAIL; ?><br>
      Phone: <?php echo SITE_PHONE; ?><br>
      Website: home-sure.in</p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
