provider "aws" {
  region = "us-west-2"
}

resource "aws_s3_bucket" "react_bucket" {
  bucket = "${var.bucket_name}"
  acl    = "public-read"
  policy = <<EOF
  {
  "Id": "bucket_policy_site",
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "bucket_policy_site_main",
      "Action": [
        "s3:GetObject"
      ],
      "Effect": "Allow",
      "Resource": "arn:aws:s3:::${var.bucket_name}/*",
      "Principal": "*"
    }
  ]
}
EOF

  website {
    index_document = "index.html"
  }

  cors_rule {
    allowed_headers = ["*"]
    allowed_methods = ["PUT", "POST"]
    allowed_origins = ["*"]
    expose_headers  = ["ETag"]
    max_age_seconds = 3000
  }
}

resource "aws_s3_bucket_object" "build" {
  bucket = "${var.bucket_name}"
  key    = "bundle.js"
  source = "../../app/build/index.bundle.js"

  content_type = "text/html"
}

resource "aws_s3_bucket_object" "index" {
  bucket = "${var.bucket_name}"
  key    = "index.html"
  source = "../../app/public/index.html"
}

resource "aws_cloudfront_origin_access_identity" "default" {

}
resource "aws_cloudfront_distribution" "s3_distribution" {
  origin {
    domain_name = "${aws_s3_bucket.react_bucket.bucket_domain_name}"
    origin_id   = "MyReactApp"

    s3_origin_config {
      origin_access_identity = "${aws_cloudfront_origin_access_identity.default.cloudfront_access_identity_path}"
    }
  }
  restrictions {
    geo_restriction {
      restriction_type = "whitelist"
      locations        = ["US", "CA", "GB", "DE"]
    }
  }
  enabled = true

  default_cache_behavior = {
    allowed_methods  = ["DELETE", "GET", "HEAD", "OPTIONS", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "MyReactApp"

    forwarded_values {
      query_string = false

      cookies {
        forward = "none"
      }
    }

    viewer_protocol_policy = "allow-all"
    min_ttl                = 0
    default_ttl            = 3600
    max_ttl                = 86400
  }

  viewer_certificate {
    cloudfront_default_certificate = true
  }
}
