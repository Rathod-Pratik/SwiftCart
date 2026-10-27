<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Account Approved - SwiftCart</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
            display: block;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f1f5f9;
            padding: 40px 0 50px 0;
        }
        .main-card {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 620px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }
        .header-bg {
            background: linear-gradient(135deg, #1b3837 0%, #295553 50%, #3d6f6d 100%);
            padding: 36px 40px 32px 40px;
            text-align: center;
        }
        .brand-title {
            color: #ffffff;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .brand-tagline {
            color: #c7e3e0;
            font-size: 13px;
            font-weight: 500;
            margin-top: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .content-body {
            padding: 40px 40px 32px 40px;
        }
        .badge {
            display: inline-block;
            background-color: #d1fae5;
            color: #065f46;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 9999px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .headline {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            margin: 0 0 16px 0;
        }
        .lead-text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 24px 0;
        }
        .feature-card {
            background: linear-gradient(to right, #f4fbf9, #ffffff);
            border: 1px solid #cceae5;
            border-left: 4px solid #3d6f6d;
            border-radius: 12px;
            padding: 22px 24px;
            margin-bottom: 24px;
        }
        .feature-title {
            font-size: 16px;
            font-weight: 700;
            color: #1b3837;
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
        }
        .feature-desc {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 16px 0;
        }
        .btn-primary {
            display: inline-block;
            background-color: #3d6f6d;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            padding: 12px 26px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(61, 111, 109, 0.25);
            transition: background-color 0.15s ease;
        }
        .action-box {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 22px 24px;
            margin-bottom: 24px;
        }
        .action-box-title {
            font-size: 15px;
            font-weight: 700;
            color: #92400e;
            margin: 0 0 8px 0;
        }
        .action-box-desc {
            font-size: 14px;
            line-height: 1.6;
            color: #78350f;
            margin: 0 0 14px 0;
        }
        .btn-warning {
            display: inline-block;
            background-color: #b45309;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
        }
        .checklist-item {
            font-size: 13px;
            color: #78350f;
            margin-bottom: 6px;
            padding-left: 18px;
            position: relative;
        }
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .info-title {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 12px 0;
        }
        .details-table {
            width: 100%;
            font-size: 14px;
        }
        .details-table td {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .details-label {
            color: #64748b;
            font-weight: 500;
            width: 40%;
        }
        .details-val {
            color: #0f172a;
            font-weight: 600;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 28px 40px;
            text-align: center;
        }
        .footer-text {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
            margin: 0 0 12px 0;
        }
        .footer-links a {
            color: #3d6f6d;
            font-size: 12px;
            text-decoration: none;
            font-weight: 600;
            margin: 0 10px;
        }
        @media only screen and (max-width: 640px) {
            .wrapper {
                padding: 16px 8px;
            }
            .content-body, .header-bg, .footer {
                padding: 24px 20px !important;
            }
            .headline {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" class="wrapper" width="100%">
        <tr>
            <td align="center">
                <table role="presentation" class="main-card" width="100%">
                    <!-- Header -->
                    <tr>
                        <td class="header-bg">
                            <h1 class="brand-title">SwiftCart</h1>
                            <p class="brand-tagline">Vendor Partner Portal</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="content-body">
                            <div class="badge">Application Approved</div>

                            <h2 class="headline">Congratulations! Your Vendor Account is Ready</h2>

                            <p class="lead-text">
                                Hello <strong>{{ $user->name ?? $vendor->store_name }}</strong>,
                            </p>
                            <p class="lead-text">
                                We are excited to inform you that your vendor application for <strong>{{ $vendor->store_name }}</strong> has been reviewed and officially accepted by the SwiftCart admin team. Your seller account is now active, and you have full access to our seller marketplace.
                            </p>

                            <!-- Publish Products Section -->
                            <div class="feature-card">
                                <h3 class="feature-title">
                                    &#128640; Start Publishing Your Products
                                </h3>
                                <p class="feature-desc">
                                    Your store is live! You can immediately list new products, set stock quantities, add pricing and discounts, and showcase your items to thousands of buyers on SwiftCart.
                                </p>
                                <a href="{{ $publishProductUrl }}" class="btn-primary" target="_blank">
                                    Publish Products Now &rarr;
                                </a>
                            </div>

                            <!-- Bank Details Action Required -->
                            <div class="action-box">
                                <h3 class="action-box-title">
                                    &#9888;&#65039; Action Required: Update Bank Details for Payouts
                                </h3>
                                <p class="action-box-desc">
                                    To receive timely settlement payouts and disbursements for your product sales, please update your bank account information in your Vendor Profile:
                                </p>
                                <div style="margin-bottom: 14px;">
                                    <div class="checklist-item">&#8226; Account Holder Name & Bank Name</div>
                                    <div class="checklist-item">&#8226; Account Number & Account Type (Savings / Current)</div>
                                    <div class="checklist-item">&#8226; Bank IFSC Code & Branch Name</div>
                                </div>
                                <a href="{{ $profileUrl }}" class="btn-warning" target="_blank">
                                    Update Bank Details &rarr;
                                </a>
                            </div>

                            <!-- Required Documents Action Required -->
                            <div class="feature-card" style="background: #f8fafc; border: 1px solid #cbd5e1; border-left: 4px solid #64748b;">
                                <h3 class="feature-title" style="color: #334155;">
                                    &#128196; Required Verification Documents
                                </h3>
                                <p class="feature-desc">
                                    Please ensure all required business and KYC documents are fully uploaded and up-to-date in your profile to maintain uninterrupted seller operations:
                                </p>
                                <ul style="margin: 0 0 16px 0; padding-left: 20px; font-size: 13px; color: #475569; line-height: 1.6;">
                                    <li><strong>Identity Proof:</strong> Government-issued ID (Aadhaar Card, Passport, or Voter ID)</li>
                                    <li><strong>Business Certificate:</strong> Company Registration or Shop Establishment License</li>
                                    <li><strong>Tax Information:</strong> GSTIN / Tax Identification Number</li>
                                    <li><strong>Pickup Address:</strong> Warehouse / Dispatch location for smooth order pickups</li>
                                </ul>
                                <a href="{{ $profileUrl }}" class="btn-primary" style="background-color: #475569;" target="_blank">
                                    Verify Documents &rarr;
                                </a>
                            </div>

                            <!-- Account Summary -->
                            <div class="info-card">
                                <h4 class="info-title">Account Summary</h4>
                                <table class="details-table" role="presentation">
                                    <tr>
                                        <td class="details-label">Store Name</td>
                                        <td class="details-val">{{ $vendor->store_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Registered Email</td>
                                        <td class="details-val">{{ $user->email ?? $vendor->support_email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Contact Phone</td>
                                        <td class="details-val">{{ $vendor->phone ?? $user->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Account Status</td>
                                        <td class="details-val" style="color: #059669;">&#10003; Approved & Active</td>
                                    </tr>
                                </table>
                            </div>

                            <p class="lead-text" style="margin-bottom: 8px;">
                                Need help getting started? Visit your <a href="{{ $dashboardUrl }}" style="color: #3d6f6d; font-weight: 600; text-decoration: underline;">Vendor Dashboard</a> to monitor sales, manage orders, and track customer inquiries.
                            </p>
                            <p class="lead-text" style="margin-bottom: 0;">
                                Welcome aboard and happy selling!<br>
                                <strong>The SwiftCart Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p class="footer-links">
                                <a href="{{ $dashboardUrl }}">Dashboard</a> &bull;
                                <a href="{{ $publishProductUrl }}">Publish Products</a> &bull;
                                <a href="{{ $profileUrl }}">Profile & Settings</a>
                            </p>
                            <p class="footer-text">
                                &copy; {{ date('Y') }} SwiftCart Marketplace. All rights reserved.<br>
                                You received this notification because your SwiftCart vendor application was approved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
