provider "aws" {
  profile = "default"
  region = "us-west-2"
}

resource "aws_instance" "cart-api-web" {
  ami = "ami-830c94e3"
  instance_type = "t2.micro"
  subnet_id = aws_subnet.cart-api-subnet-public.id
  vpc_security_group_ids = [
    aws_security_group.allow-ssh.id,
    aws_security_group.allow-all-outbound.id,
    aws_security_group.allow-http.id,
  ]
  key_name = "cart-api-key"
  user_data = <<EOF
  #!/bin/bash
cd /tmp
echo '#!/bin/bash
sudo apt-get update
sudo apt-get install nginx
sudo ufw allow 'Nginx HTTP'
sudo nginx
' >> init.sh
chmod +x init.sh
/bin/su -c "/tmp/init.sh" - ec2-user
rm init.sh
EOF
}

resource "aws_key_pair" "cart-api-key" {
  key_name = "cart-api-key"
  public_key = file("./aws_terraform_test.pub")
}

resource "aws_vpc" "cart-api" {
  cidr_block = "10.0.0.0/16"
  enable_dns_hostnames = true
}

resource "aws_subnet" "cart-api-subnet-public" {
  availability_zone_id = "usw2-az1"
  cidr_block = "10.0.0.0/24"
  vpc_id = aws_vpc.cart-api.id
}

resource "aws_security_group" "allow-all-outbound" {
  name = "allow-all-outbound"
  description = "Allow all outbound traffic"
  vpc_id = aws_vpc.cart-api.id

  egress {
    from_port = 0
    to_port = 0
    protocol = "-1"
    cidr_blocks = [
      "0.0.0.0/0"]
  }
}

resource "aws_security_group" "allow-ssh" {
  name = "allow-ssh"
  description = "Allow SSH inbound traffic"
  vpc_id = aws_vpc.cart-api.id

  ingress {
    from_port = 22
    to_port = 22
    protocol = "tcp"
    cidr_blocks = [
      "0.0.0.0/0"]
  }
}

resource "aws_security_group" "allow-http" {
  name = "allow-http"
  description = "Allow HTTP inbound traffic"
  vpc_id = aws_vpc.cart-api.id

  ingress {
    from_port = 80
    to_port = 80
    protocol = "tcp"
    cidr_blocks = [
      "0.0.0.0/0"]
  }
}

resource "aws_route_table_association" "microservices-demo-subnet-public" {
  subnet_id = aws_subnet.cart-api-subnet-public.id
  route_table_id = aws_route_table.allow-outgoing-access.id
}

resource "aws_route_table" "allow-outgoing-access" {
  vpc_id = aws_vpc.cart-api.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.cart-api.id
  }
}
resource "aws_internet_gateway" "cart-api" {
  vpc_id = aws_vpc.cart-api.id
}

resource "aws_eip" "api-gateway-eip" {
  instance = aws_instance.cart-api-web.id
}