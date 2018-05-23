output "s3_website_domain" {
  value = "${aws_s3_bucket.react_bucket.website_domain}"
}

output "s3_website_endpoint" {
  value = "${aws_s3_bucket.react_bucket.website_endpoint}"
}

output "cloudfront_domain" {
  value = "${aws_cloudfront_distribution.s3_distribution.domain_name}"
}
