# Installation guide for Kip
Here is the guide to getting started using KIP.


## Configuration

To override default configurations, you can create a `.env.local` at the root of the project.

### Local Storage

By default, and during development process, the default storage location is set to `var/storage`.
You can override this setting by setting your env variable `FILE_STORAGE`

> In the docker container, by default, FILE_STORAGE is set to `/storage`

### S3 Storage
In order to enable S3 Storage, first you will have to change the file storage for an s3 storage path, 
then set all the s3 settings.

```dotenv
FILE_STORAGE=s3://BUCKET
S3_VERSION=latest
S3_REGION=my-region
S3_KEY=my-key
S3_SECRET=my-secret
S3_ENDPOINT=https://s3-endpoint.url/
```
By default, values are set to default docker for minio
```dotenv
S3_REGION=minio
S3_KEY=minioadmin
S3_SECRET=minioadmin
S3_ENDPOINT=http://localhost:9000
```


For AWS S3 it would look like something like this
```dotenv
FILE_STORAGE=s3://BUCKET
S3_VERSION=latest
S3_REGION=eu-west-1
S3_KEY=my-key
S3_SECRET=my-secret
S3_ENDPOINT=https://s3.eu-west-1.amazonaws.com/
```

You can find more details about your region on [AWS documentation](https://docs.aws.amazon.com/general/latest/gr/rande.html).

For other providers you can check [Scaleway Object Storage](https://www.scaleway.com/en/docs/object-storage-feature/) or [digitalOcean Spaces](https://www.digitalocean.com/docs/spaces/resources/s3-sdk-examples/)